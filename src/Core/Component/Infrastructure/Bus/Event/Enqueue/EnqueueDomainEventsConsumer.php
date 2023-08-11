<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Event\Enqueue;

use RuntimeException;
use App\Core\Shared\Domain\Utils;
use App\Core\Component\Infrastructure\Bus\Event\DomainEventMapping;

use function Lambdish\Phunctional\each;

class EnqueueDomainEventsConsumer
{
    public function __construct(
        private DomainEventMapping $domainEventMapping
    ) {
    }

    public function consume(callable $subscribers, int $eventsToConsume): void
    {
        $events = [];
        each($this->executeSubscribers($subscribers), $events);
    }

    private function executeSubscribers(callable $subscribers): callable
    {
        return function (array $rawEvent) use ($subscribers): void {
            try {
                $domainEventClass = $this->domainEventMapping->for($rawEvent['name']);
                $domainEvent = $domainEventClass::fromPrimitives(
                    $rawEvent['aggregate_id'],
                    Utils::jsonDecode($rawEvent['body']),
                    $rawEvent['id'],
                    $this->formatDate($rawEvent['occurred_on'])
                );
                $subscribers($domainEvent);
            } catch (RuntimeException) {
            }
        };
    }

    private function formatDate(string $stringDate): string
    {
        return Utils::dateToString(new \DateTimeImmutable($stringDate));
    }
}
