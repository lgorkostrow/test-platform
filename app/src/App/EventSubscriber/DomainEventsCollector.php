<?php

declare(strict_types=1);

namespace App\App\EventSubscriber;

use App\Domain\Common\Event\DomainEventInterface;
use App\Domain\Common\Event\RaiseEventsInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * On postPersist, postUpdate, postRemove, postFlush events we save in the events variable domain
 * events of entities. On postFlush using EventDispatcherInterface we call events from the events variable.
 */
class DomainEventsCollector implements EventSubscriber
{
    /**
     * @var DomainEventInterface[] Domain events that are queued
     */
    private array $events = [];

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
            Events::postFlush,
        ];
    }

    public function postPersist(LifecycleEventArgs $event)
    {
        $this->doCollect($event);
    }

    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->doCollect($event);
    }

    public function postRemove(LifecycleEventArgs $event)
    {
        $this->doCollect($event);
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $this->dispatchCollectedEvents();
    }

    public function dispatchCollectedEvents(): void
    {
        $events = $this->events;
        $this->events = [];

        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }

        if ($this->events) {
            $this->dispatchCollectedEvents();
        }
    }

    private function doCollect(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();

        if (!$entity instanceof RaiseEventsInterface) {
            return;
        }

        foreach ($entity->popEvents() as $event) {
            $this->events[spl_object_hash($event)] = $event;
        }
    }
}
