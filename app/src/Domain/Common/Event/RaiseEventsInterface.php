<?php

namespace App\Domain\Common\Event;

interface RaiseEventsInterface
{
    /**
     * Return events raised by the entity and clear those.
     *
     * @return DomainEventInterface[]
     */
    public function popEvents(): array;
}
