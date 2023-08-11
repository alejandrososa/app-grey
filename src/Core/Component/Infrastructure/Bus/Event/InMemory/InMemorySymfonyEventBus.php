<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Event\InMemory;

use App\Core\Shared\Domain\Bus\Event\EventBus;
use App\Core\Shared\Domain\Bus\Event\DomainEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;

class InMemorySymfonyEventBus implements EventBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function publish(DomainEvent ...$domainEvent): void
    {
        foreach ($domainEvent as $event) {
            try {
                $this->messageBus->dispatch($event);
            } catch (NoHandlerForMessageException) {
            }
        }
    }
}
