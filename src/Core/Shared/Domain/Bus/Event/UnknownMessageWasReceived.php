<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Bus\Event;

final class UnknownMessageWasReceived implements Event
{
    /** @param array<mixed> $body */
    public function __construct(private array $body)
    {
    }

    /** @return string[] */
    public function toPrimitives(): array
    {
        return $this->body;
    }

    public static function eventName(): string
    {
        return 'core.shared.unknown';
    }

    public function aggregateId(): string
    {
        return '';
    }

    public function eventId(): string
    {
        return '';
    }

    public function occurredOn(): string
    {
        return '';
    }

    /** @param array<mixed> $data */
    public static function fromPrimitives(array $data): Event
    {
        return new self($data);
    }
}
