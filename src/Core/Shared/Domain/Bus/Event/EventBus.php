<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Bus\Event;

interface EventBus
{
    public function publish(DomainEvent ...$domainEvent): void;
}
