<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Event\InMemory;

use App\Core\Shared\Domain\Bus\Event\EventBus;
use App\Core\Shared\Domain\Bus\Event\DomainEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class InMemorySymfonyEventBus implements EventBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function publish(DomainEvent ...$domainEvent): void
    {
        foreach ($domainEvent as $event) {
            $this->messageBus->dispatch($event);
        }
    }
}
