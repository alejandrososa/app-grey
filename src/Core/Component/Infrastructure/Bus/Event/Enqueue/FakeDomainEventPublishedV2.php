<?php

namespace App\Core\Component\Infrastructure\Bus\Event\Enqueue;

use App\Core\Shared\Domain\Bus\Event\DomainEvent;

final class FakeDomainEventPublishedV2 extends DomainEvent
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
        return 'App.core.component.v2.test';
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
        return $this->content();
    }

    public function content(): array
    {
        return $this->body();
    }
}
