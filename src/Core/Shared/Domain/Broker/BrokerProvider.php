<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Broker;

use App\Core\Shared\Domain\Bus\Query\Query;

interface BrokerProvider
{
    public function addMessageQuery(string $contextName, string $messageQuery): void;

    /** @param array<mixed> $args */
    public function getMessageQuery(string $contextName, array $args = []): Query;

    /** @return array<mixed> */
    public function getAllMessageQueries(): array;
}
