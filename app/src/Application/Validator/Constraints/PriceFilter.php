<?php

declare(strict_types=1);

namespace App\Application\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class PriceFilter extends Constraint
{
    public const INVALID_FORMAT_ERROR = 'bacdb448-f348-406e-b683-be284eec48ff';
    public const IS_NEGATIVE_ERROR = 'af4ba10e-38f5-452e-8dc4-4bd524bc76d5';
    public const INVALID_TYPE_ERROR = 'ba785a8c-82cb-4283-967c-3cf342181b40';
    public const INVALID_MAX_VALUE = 'b5c7980d-37c4-4ff7-97c4-37977e260851';

    protected static $errorNames = [
        self::INVALID_FORMAT_ERROR => 'INVALID_FORMAT_ERROR',
        self::IS_NEGATIVE_ERROR => 'IS_NEGATIVE_ERROR',
        self::INVALID_TYPE_ERROR => 'INVALID_TYPE_ERROR',
        self::INVALID_MAX_VALUE => 'INVALID_MAX_VALUE',
    ];
}
