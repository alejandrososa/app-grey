<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Broker;

use App\Core\Shared\Domain\Bus\Query\Response;

interface Broker
{
    /** @param array<string, object> $args */
    public function ask(string $contextName, array $args = []): Response;
}
