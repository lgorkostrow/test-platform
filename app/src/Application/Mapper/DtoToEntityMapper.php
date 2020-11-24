<?php

declare(strict_types=1);

namespace App\Application\Mapper;

use App\Application\Utils\ArrayUtils;
use ReflectionClass;

class DtoToEntityMapper
{
    /**
     * @var object
     */
    private object $entity;

    /**
     * @var object
     */
    private object $dto;

    /**
     * @var ReflectionClass
     */
    private ReflectionClass $entityReflection;

    /**
     * @var ReflectionClass
     */
    private ReflectionClass $dtoReflection;

    public function __construct($dto)
    {
        $this->dto = $dto;
        $this->dtoReflection = new ReflectionClass($dto);
    }

    public function map(array $fields, $entity)
    {
        $this->entityReflection = new ReflectionClass($entity);

        if (is_object($entity)) {
            $this->entity = $entity;
        } else {
            $this->entity = $this->entityReflection->newInstanceWithoutConstructor();
        }

        if (ArrayUtils::isAssocArray($fields)) {
            foreach ($fields as $dtoField => $entityField) {
                $dtoField = is_integer($dtoField) ? $entityField : $dtoField;

                $this->fillField($dtoField, $entityField);
            }
        } else {
            foreach ($fields as $field) {
                $this->fillField($field, $field);
            }
        }

        return $this->entity;
    }

    public function getDtoReflection(): ReflectionClass
    {
        return $this->dtoReflection;
    }

    private function fillField(string $dtoFieldName, string $entityFieldName)
    {
        $this->checkField($this->dtoReflection, $dtoFieldName);

        $dtoProperty = $this->dtoReflection->getProperty($dtoFieldName);
        $dtoProperty->setAccessible(true);

        if (str_contains($entityFieldName, '.')) {
            $parts = explode('.', $entityFieldName);

            $this->fillEmbedded($this->entityReflection, $this->entity, new \ArrayIterator($parts), $dtoProperty->getValue($this->dto));
        } else {
            $this->checkField($this->entityReflection, $entityFieldName);

            $entityProperty = $this->entityReflection->getProperty($entityFieldName);
            $entityProperty->setAccessible(true);
            $entityProperty->setValue(
                $this->entity,
                $dtoProperty->getValue($this->dto)
            );
        }
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $fieldName
     * @return bool
     */
    private function checkField(ReflectionClass $reflectionClass, string $fieldName)
    {
        if (!$reflectionClass->hasProperty($fieldName)) {
            throw new \InvalidArgumentException(sprintf(
                'Property %s of class %s does not exist',
                $fieldName,
                $reflectionClass->name
            ));
        }

        return true;
    }

    private function fillEmbedded(ReflectionClass $reflectionClass, object $object, \Iterator $fields, $value): void
    {
        $fieldName = $fields->current();
        $this->checkField($reflectionClass, $fieldName);

        $property = $reflectionClass->getProperty($fieldName);
        $property->setAccessible(true);

        $embeddedType = $property->getType();
        if (null === $embeddedType) {
            throw new \InvalidArgumentException(sprintf('Undefined type of property %s', $fieldName));
        }

        if ($embeddedType->isBuiltin()) {
            if ($embeddedType->getName() !== gettype($value)) {
                return;
            }

            $property->setValue(
                $object,
                $value
            );

            return;
        }

        $embeddedReflection = new ReflectionClass($embeddedType->getName());

        $embedded = $embeddedReflection->newInstanceWithoutConstructor();
        $property->setValue(
            $object,
            $embedded,
        );

        $fields->next();

        $this->fillEmbedded($embeddedReflection, $embedded, $fields, $value);
    }
}
