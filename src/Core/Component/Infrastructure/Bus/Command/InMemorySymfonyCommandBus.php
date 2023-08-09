<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Command;

use App\Core\Shared\Domain\Bus\Command\Command;
use App\Core\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

final class InMemorySymfonyCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (NoHandlerForMessageException) {
            throw new CommandNotRegisteredError($command);
        } catch (HandlerFailedException $error) {
            throw $error->getPrevious() ?? $error;
        }
    }
}
