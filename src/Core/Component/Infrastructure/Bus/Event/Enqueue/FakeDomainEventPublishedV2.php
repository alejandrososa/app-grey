<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Event\Enqueue;

use App\Core\Shared\Domain\Bus\Event\DomainEvent;

final class FakeDomainEventPublishedV2 extends DomainEvent
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
        return 'app.core.component.v2.test';
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
        return $this->content();
    }

    /** @return array<mixed> */
    public function content(): array
    {
        return $this->body();
    }
}
