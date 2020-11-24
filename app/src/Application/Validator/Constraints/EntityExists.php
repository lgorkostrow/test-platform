<?php

declare(strict_types=1);

namespace App\Application\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class EntityExists extends Constraint
{
    const ENTITY_NOT_FOUND = '05a3def4-ce5b-44c9-bdcd-5fd520f78fee';

    protected static $errorNames = [
        self::ENTITY_NOT_FOUND => 'ENTITY_NOT_FOUND',
    ];

    public $message = 'Entity not found.';
    public $class;
    public $params = [];
    public $callback = null;

    public function getRequiredOptions(): array
    {
        return [
            'class',
        ];
    }

    public function getDefaultOption(): string
    {
        return 'class';
    }
}
