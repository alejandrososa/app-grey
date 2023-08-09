<?php

namespace App\Core\Component\Infrastructure\Bus;

final class BusFactory implements BusFactoryInterface
{
    public function __construct(private BusChain $busChain)
    {
    }

    public function getQueries(): ?array
    {
        $queries = $this->busChain->getHandlers('query');

        $handlers = [];
        foreach ($queries as $query) {
            $handlers[] = $query;
        }

        return $handlers;
    }

    public function getCommands(): ?array
    {
        $commands = $this->busChain->getHandlers('command');

        $handlers = [];
        foreach ($commands as $command) {
            $handlers[] = $command;
        }

        return $handlers;
    }

    public function getSubscribers(): ?array
    {
        $commands = $this->busChain->getHandlers('domain_event_subscriber');

        $handlers = [];
        foreach ($commands as $command) {
            $handlers[] = $command;
        }

        return $handlers;
    }

    public function getDomainEvents(): ?array
    {
        return $this->busChain->getDomainEvents();
    }
}
