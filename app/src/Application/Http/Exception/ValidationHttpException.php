<?php

declare(strict_types=1);

namespace App\Application\Http\Exception;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationHttpException extends UnprocessableEntityHttpException
{
    private ConstraintViolationListInterface $errors;

    public function __construct(ConstraintViolationListInterface $errors)
    {
        parent::__construct();

        $this->errors = $errors;
    }

    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}
