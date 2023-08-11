<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus;

interface BusFactoryInterface
{
    /** @return array<mixed>|null */
    public function getQueries(): ?array;

    /** @return array<mixed>|null */
    public function getCommands(): ?array;

    /** @return array<mixed>|null */
    public function getSubscribers(): ?array;

    /** @return array<mixed>|null */
    public function getDomainEvents(): ?array;
}
