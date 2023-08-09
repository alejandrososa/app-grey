<?php

namespace App\Core\Component\Infrastructure\Bus\Event\Enqueue;

use function Lambdish\Phunctional\each;
use App\Core\Shared\Domain\Bus\Event\DomainEvent;
use App\Core\Shared\Domain\Bus\Event\EventBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

class EnqueueEventBus implements EventBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function publish(DomainEvent ...$domainEvent): void
    {
        each($this->publisher(), $domainEvent);
    }

    private function publisher(): callable
    {
        return function (DomainEvent $domainEvent): void {
            try {
                $this->messageBus->dispatch($domainEvent);
            } catch (NoHandlerForMessageException) {
                // nothing to do
            } catch (HandlerFailedException $error) {
                throw $error->getPrevious() ?? $error;
            }
        };
    }
}
