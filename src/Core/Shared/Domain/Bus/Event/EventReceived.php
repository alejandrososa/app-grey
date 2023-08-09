<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Bus\Event;

interface EventReceived
{
    public function aggregateId(): string;

    public function eventId(): string;

    public function occurredOn(): string;

    /**
     * @return array<string>
     */
    public function toPrimitives(): array;
}
