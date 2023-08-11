<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Event\Enqueue;

use App\Core\Shared\Domain\Bus\Event\DomainEvent;

final class FakeDomainEventPublishedV1 extends DomainEvent
{
    /**
     * @param array<mixed> $body
     */
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
        return 'app.core.component.v1.fake';
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

    /** @return array<mixed> */
    public function toPrimitives(): array
    {
        return $this->myData();
    }

    /** @return array<mixed> */
    public function myData(): array
    {
        return $this->body();
    }
}
