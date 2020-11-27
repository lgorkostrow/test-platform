<?php

declare(strict_types=1);

namespace App\Application\Utils;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;

class ValidatorUtils
{
    public static function rebuildViolationWithPropertyPath(
        ConstraintViolationInterface $violation,
        string $propertyPath
    ): ConstraintViolationInterface {
        return new ConstraintViolation(
            $violation->getMessage(),
            $violation->getMessageTemplate(),
            $violation->getParameters(),
            $violation->getRoot(),
            $propertyPath,
            $violation->getInvalidValue(),
            $violation->getPlural(),
            $violation->getCode(),
            $violation->getConstraint(),
            $violation->getCause()
        );
    }
}
