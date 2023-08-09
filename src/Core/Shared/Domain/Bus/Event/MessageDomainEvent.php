<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Bus\Event;

final class MessageDomainEvent implements EventReceived
{
    /** @param string[] $body */
    public function __construct(
        private string $aggregateId,
        private array $body,
        private string $eventId,
        private string $eventName,
        private string $occurredOn
    ) {
    }

    /** @param array<string, string> $body */
    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $eventName,
        string $occurredOn
    ): MessageDomainEvent {
        return new self(
            aggregateId: $aggregateId,
            body: $body,
            eventId: $eventId,
            eventName: $eventName,
            occurredOn: $occurredOn
        );
    }

    /** @return array{'aggregate_id': string, 'event_id': string, 'event_name': string, 'body': string[], 'occurred_on': string} */
    public function toPrimitives(): array
    {
        return [
            'aggregate_id' => $this->aggregateId(),
            'event_id' => $this->eventId(),
            'event_name' => $this->eventName(),
            'body' => $this->body(),
            'occurred_on' => $this->occurredOn(),
        ];
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    /** @return array<string, string> */
    public function body(): array
    {
        return $this->body;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function eventName(): string
    {
        return $this->eventName;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }
}
