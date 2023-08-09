<?php

namespace App\Core\Shared\Domain\Broker;

use App\Core\Shared\Domain\Bus\Query\Query;

interface BrokerProvider
{
    public function addMessageQuery(string $contextName, string $messageQuery);

    /** @param array<string, string> $args */
    public function getMessageQuery(string $contextName, array $args = []): Query;

    /** @return  array<string, object> $args */
    public function getAllMessageQueries(): array;
}
