<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Bus;

use App\Core\Shared\Domain\Bus\Command\Command;
use App\Core\Shared\Domain\Bus\Event\DomainEvent;
use App\Core\Shared\Domain\Bus\Query\Query;
use App\Core\Shared\Domain\Bus\Query\Response;

interface BusInterface
{
    public function dispatch(Command $command): void;

    public function ask(Query $query): ?Response;

    public function publish(DomainEvent ...$domainEvent): void;
}
