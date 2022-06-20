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
    protected $token;
    protected $logger;

    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->container = $container;
        $this->logger = $logger;
        $this->token = $this->container->get('security.token_storage')->getToken();
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $log = new LogEntry($event, $this->token ?? null);
        $this->logger->info('', $log->jsonSerialize());
        //$this->container->get('monolog.logger.http_log_database')->info(null, ["log" => $log]);

        //$this->container->get('monolog.logger.http_log_file')->info(json_encode($log));

    }
}
