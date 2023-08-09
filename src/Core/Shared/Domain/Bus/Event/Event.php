<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Bus\Event;

interface Event
{
    public function aggregateId(): string;

    public function eventId(): string;

    public function occurredOn(): string;
}
