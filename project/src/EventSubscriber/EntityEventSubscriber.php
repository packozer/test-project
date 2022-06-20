<?php
namespace App\EventSubscriber;

use App\Entity\Customer;
use App\Entity\Product;
use App\Enum\StatusEnum;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class EntityEventSubscriber implements EventSubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Product || $entity instanceof Customer) {
            if ($entity->getCreatedAt() === null) {
                $entity->setStatus(StatusEnum::STATUS_NEW);
                $entity->setCreatedAtValue();
            }
        }
    }
}