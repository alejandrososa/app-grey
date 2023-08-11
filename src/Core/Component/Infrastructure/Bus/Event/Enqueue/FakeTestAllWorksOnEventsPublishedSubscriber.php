<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Event\Enqueue;

use App\Core\Shared\Domain\Logger\Logger;
use App\Core\Shared\Domain\Bus\Event\MessageDomainEvent;
use App\Core\Shared\Domain\Bus\Event\DomainEventSubscriber;

class FakeTestAllWorksOnEventsPublishedSubscriber implements DomainEventSubscriber
{
    public function __construct(private Logger $logger)
    {
    }

    public function __invoke(MessageDomainEvent $messageDomainEvent): void
    {
        $this->logger->info(
            sprintf('Domain event <%s> received', $messageDomainEvent->eventName()),
            $messageDomainEvent->toPrimitives()
        );
    }

    /** @return array<string> */
    public static function subscribedTo(): array
    {
        return [
            'App.core.component.v1.fake',
        ];
    }
}
