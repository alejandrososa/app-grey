<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Broker;

use App\Core\Shared\Domain\Broker\Broker;
use App\Core\Shared\Domain\Bus\BusInterface;
use App\Core\Shared\Domain\Bus\Query\Response;
use App\Core\Shared\Domain\Broker\BrokerProvider;

class BrokerComponent implements Broker
{
    public function __construct(private BrokerProvider $brokerProvider, private BusInterface $bus)
    {
    }

    /** @param array<string, object> $args */
    public function ask(string $contextName, array $args = []): Response
    {
        $query = $this->brokerProvider->getMessageQuery($contextName, $args);

        return $this->bus->ask($query);
    }
}
