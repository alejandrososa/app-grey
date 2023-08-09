<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Query;

use App\Core\Shared\Domain\Bus\Query\Query;
use App\Core\Shared\Domain\Bus\Query\QueryBus;
use App\Core\Shared\Domain\Bus\Query\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class InMemorySymfonyQueryBus implements QueryBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function ask(Query $query): ?Response
    {
        try {
            /** @var HandledStamp $stamp */
            $stamp = $this->messageBus->dispatch($query)->last(HandledStamp::class);

            return $stamp->getResult();
        } catch (NoHandlerForMessageException) {
            throw new QueryNotRegisteredError($query);
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious();
//            throw new QueryThrowError($e->getNestedExceptions()[0]?->getMessage());
        }
    }
}
