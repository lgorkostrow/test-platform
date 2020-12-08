<?php

declare(strict_types=1);

namespace App\Application\EventListener\Doctrine;

use App\Domain\Common\Entity\TimestampableInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;

class TimestampsFiller
{
    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$this->isTimestampable($entity)) {
                continue;
            }

            $this->setCreatedAt($entity);
            $this->recomputeEntityChangeSet($em, $entity);
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$this->isTimestampable($entity)) {
                continue;
            }

            $this->setUpdatedAt($entity);
            $this->recomputeEntityChangeSet($em, $entity);
        }
    }

    private function recomputeEntityChangeSet(EntityManagerInterface $em, $entity): void
    {
        $uow = $em->getUnitOfWork();
        $meta = $em->getClassMetadata(get_class($entity));

        if (!$uow->isEntityScheduled($entity)) {
            $uow->computeChangeSet($meta, $entity);
        }

        $uow->recomputeSingleEntityChangeSet($meta, $entity);
    }

    private function setCreatedAt(TimestampableInterface $entity): void
    {
        $entity->setCreatedAt(new DateTime());
    }

    private function setUpdatedAt(TimestampableInterface $entity): void
    {
        $entity->setUpdatedAt(new DateTime());
    }

    private function isTimestampable($entity): bool
    {
        return $entity instanceof TimestampableInterface;
    }
}
