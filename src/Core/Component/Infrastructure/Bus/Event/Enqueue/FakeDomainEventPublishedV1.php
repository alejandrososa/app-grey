<?php

namespace App\Core\Component\Infrastructure\Bus\Event\Enqueue;

use App\Core\Shared\Domain\Bus\Event\DomainEvent;

final class FakeDomainEventPublishedV1 extends DomainEvent
{
    public function __construct(
        string $id,
        array $body,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct(
            aggregateId: $id,
            body: $body,
            eventId: $eventId,
            occurredOn: $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'App.core.component.v1.fake';
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self(
            id: $aggregateId,
            body: $body,
            eventId: $eventId,
            occurredOn: $occurredOn
        );
    }

    public function toPrimitives(): array
    {
        return $this->myData();
    }

    public function myData(): array
    {
        return $this->body();
    }
}
