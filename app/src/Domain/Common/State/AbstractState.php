<?php

declare(strict_types=1);

namespace App\Domain\Common\State;

abstract class AbstractState
{
    const NAME = '';

    /**
     * @var array
     */
    protected array $next = [];

    public function canBeChangedTo(AbstractState $state): bool
    {
        return in_array(get_class($state), $this->next, true);
    }

    public function allowsModification(): bool
    {
        return false;
    }
}
