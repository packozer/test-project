<?php

namespace App\EventSubscriber;

use App\Entity\LogEntry;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Psr\Container\ContainerInterface;

class LogEntrySubscriber implements EventSubscriberInterface
{
    protected $container;
    protected $user;
    protected $logger;

    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->container = $container;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $log = new LogEntry($event);
        $this->logger->info('', $log->jsonSerialize());
    }
}
