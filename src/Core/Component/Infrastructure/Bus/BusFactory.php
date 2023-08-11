<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus;

final class BusFactory implements BusFactoryInterface
{
    public function __construct(private BusChain $busChain)
    {
    }

    /** @return array<mixed>|null */
    public function getQueries(): ?array
    {
        $queries = $this->busChain->getHandlers('query');

        $handlers = [];
        foreach ($queries as $query) {
            $handlers[] = $query;
        }

        return $handlers;
    }

    /** @return array<mixed>|null */
    public function getCommands(): ?array
    {
        $commands = $this->busChain->getHandlers('command');

        $handlers = [];
        foreach ($commands as $command) {
            $handlers[] = $command;
        }

        return $handlers;
    }

    /** @return array<mixed>|null */
    public function getSubscribers(): ?array
    {
        $commands = $this->busChain->getHandlers('domain_event_subscriber');

        $handlers = [];
        foreach ($commands as $command) {
            $handlers[] = $command;
        }

        return $handlers;
    }

    /** @return array<mixed>|null */
    public function getDomainEvents(): ?array
    {
        return $this->busChain->getDomainEvents();
    }
}
