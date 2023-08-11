<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Event;

use App\Core\Component\Infrastructure\Bus\BusFactoryInterface;
use App\Core\Component\Infrastructure\Bus\CallableFirstParameterExtractor;

final class DomainEventSubscriberLocator
{
    /** @var array<mixed> */
    private array $mapping;

    public function __construct(BusFactoryInterface $busFactory)
    {
        $this->mapping = $busFactory->getSubscribers();
    }

    /** @return array<object> */
    public function allSubscribedTo(string $eventClass): array
    {
        $formatted = CallableFirstParameterExtractor::forPipedCallables($this->mapping);

        return $formatted[$eventClass] ?? [];
    }

    /** @return array<mixed> */
    public function all(): array
    {
        return $this->mapping;
    }
}
