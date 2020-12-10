<?php

declare(strict_types=1);

namespace App\Application\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PriceFilterValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PriceFilter) {
            throw new UnexpectedTypeException($constraint, PriceFilter::class);
        }

        if (empty($value)) {
            return;
        }

        if (!is_array($value)) {
            $this->context->buildViolation('')
                ->setCode(PriceFilter::INVALID_FORMAT_ERROR)
                ->addViolation();

            return;
        }

        if (isset($value['min'])) {
            $this->validateMinValue($value['min']);
        }

        if (isset($value['max'])) {
            $this->validateMaxValue($value['max'], $value['min'] ?? null);
        }
    }

    private function validateMinValue($value): void
    {
        if (!ctype_digit($value)) {
            $this->context->buildViolation('')
                ->setCode(PriceFilter::INVALID_TYPE_ERROR)
                ->addViolation();

            return;
        }

        if (0 > $value) {
            $this->context->buildViolation('')
                ->setCode(PriceFilter::IS_NEGATIVE_ERROR)
                ->addViolation();

            return;
        }
    }

    private function validateMaxValue($maxValue, $minValue): void
    {
        if (!ctype_digit($maxValue)) {
            $this->context->buildViolation('')
                ->setCode(PriceFilter::INVALID_TYPE_ERROR)
                ->addViolation();

            return;
        }

        if (0 > $maxValue) {
            $this->context->buildViolation('')
                ->setCode(PriceFilter::IS_NEGATIVE_ERROR)
                ->addViolation();

            return;
        }

        if ($minValue && $maxValue <= $minValue) {
            $this->context->buildViolation('')
                ->setCode(PriceFilter::INVALID_MAX_VALUE)
                ->addViolation();

            return;
        }
    }
}
