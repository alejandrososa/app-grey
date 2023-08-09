<?php

namespace App\Core\Component\Infrastructure\Bus\Event\Enqueue;

use App\Core\Shared\Domain\Bus\Event\DomainEventSubscriber;
use App\Core\Shared\Domain\Bus\Event\MessageDomainEvent;
use App\Core\Shared\Domain\Logger\Logger;

class FakeTestAllWorksOnEventsPublishedSubscriber implements DomainEventSubscriber
{
    public function __construct(private Logger $logger)
    {
    }

    public function __invoke(MessageDomainEvent $messageDomainEvent)
    {
        $this->logger->info(sprintf('Domain event <%s> received', $messageDomainEvent->eventName()), $messageDomainEvent->toPrimitives());
    }

    public static function subscribedTo(): array
    {
        return [
            'App.core.component.v1.fake',
        ];
    }
}
