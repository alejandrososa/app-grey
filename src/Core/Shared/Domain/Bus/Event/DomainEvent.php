<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Bus\Event;

use App\Core\Shared\Domain\Utils;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

abstract class DomainEvent implements Event
{
    private string $eventId;
    private string $occurredOn;

    /**
     * @param string[]|null $body
     */
    public function __construct(
        private ?string $aggregateId = null,
        private ?array $body = [],
        string $eventId = null,
        string $occurredOn = null
    ) {
        $this->eventId = $eventId ?: Uuid::v4();
        $this->occurredOn = $occurredOn ?: Utils::dateToString(new DateTimeImmutable());
    }

    /** @param string[] $body */
    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self;

    abstract public static function eventName(): string;

    /** @return array{'aggregateId': string, 'eventId': string, 'body': string[], 'occurredOn': string} */
    abstract public function toPrimitives(): array;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    /** @return string[] */
    public function body(): array
    {
        return $this->body;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }

    //    public function messageDomainEvent(): MessageDomainEvent
    //    {
    //        return MessageDomainEvent::fromPrimitives(
    //            aggregateId: $this->aggregateId,
    //            body: $this->body,
    //            eventId: $this->eventId,
    //            eventName: static::eventName(),
    //            occurredOn: $this->occurredOn
    //        );
    //    }
}
