<?php

namespace App\Core\Component\Infrastructure\Bus;

interface BusFactoryInterface
{
    public function getQueries(): ?array;

    public function getCommands(): ?array;

    public function getSubscribers(): ?array;

    public function getDomainEvents(): ?array;
}
