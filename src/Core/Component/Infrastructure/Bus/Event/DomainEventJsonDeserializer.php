<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Event;

use App\Core\Shared\Domain\Utils;
use App\Core\Shared\Domain\Bus\Event\DomainEvent;

final class DomainEventJsonDeserializer
{
    public function __construct(private DomainEventMapping $domainEventMapping)
    {
    }

    public function deserialize(string $domainEvent): DomainEvent
    {
        $eventData = Utils::jsonDecode($domainEvent);
        $eventName = $eventData['data']['type'];
        $eventClass = $this->domainEventMapping->for($eventName);

        if ($eventClass === null) {
            throw new \RuntimeException("The event <{$eventName}> doesn't exist or has no subscribers");
        }

        return $eventClass::fromPrimitives(
            $eventData['data']['attributes']['id'],
            $eventData['data']['attributes'],
            $eventData['data']['id'],
            $eventData['data']['occurred_on']
        );
    }
}
