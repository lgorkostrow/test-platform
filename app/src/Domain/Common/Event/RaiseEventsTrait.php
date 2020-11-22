<?php

declare(strict_types=1);

namespace App\Domain\Common\Event;

trait RaiseEventsTrait
{
    /**
     * @var DomainEventInterface[]
     */
    protected array $events = [];

    /**
     * @return DomainEventInterface[]
     */
    public function popEvents(): array
    {
        $events = $this->events;

        $this->events = [];

        return $events;
    }

    protected function raise(DomainEventInterface $event)
    {
        $this->events[] = $event;
    }
}
