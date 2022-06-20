<?php
namespace App\EventListener;

use App\Entity\Customer;
use App\Entity\Product;
use App\Enum\StatusEnum;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class EntitySubscriber implements EventSubscriberInterface
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
                $entity->setCreatedAtValue();
            }
            return;
        }
    }
}