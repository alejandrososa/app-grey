<?php

namespace App\Core\Component\Infrastructure\Bus;

use App\Core\Shared\Domain\Bus\BusInterface;
use App\Core\Shared\Domain\Bus\Command\Command;
use App\Core\Shared\Domain\Bus\Command\CommandBus;
use App\Core\Shared\Domain\Bus\Event\DomainEvent;
use App\Core\Shared\Domain\Bus\Event\EventBus;
use App\Core\Shared\Domain\Bus\Query\Query;
use App\Core\Shared\Domain\Bus\Query\QueryBus;
use App\Core\Shared\Domain\Bus\Query\Response;

class BusComponent implements BusInterface
{
    public function __construct(
        private ?CommandBus $commandBus,
        private ?QueryBus $queryBus,
        private ?EventBus $enqueueEventBus,
        private ?EventBus $mysqlEventBus
    ) {
    }

    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }

    public function ask(Query $query): ?Response
    {
        return $this->queryBus->ask($query);
    }

    public function publish(DomainEvent ...$domainEvent): void
    {
        $this->enqueueEventBus->publish(...$domainEvent);
        $this->mysqlEventBus->publish(...$domainEvent);
    }
}
