<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Event\Enqueue;

use App\Core\Shared\Domain\Bus\Event\EventBus;
use App\Core\Shared\Domain\Bus\Event\DomainEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;

use function Lambdish\Phunctional\each;

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
