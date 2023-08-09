<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Event;

use function Lambdish\Phunctional\reduce;
use function Lambdish\Phunctional\reindex;
use App\Core\Component\Infrastructure\Bus\BusFactoryInterface;
use RuntimeException;

final class DomainEventMapping
{
    private $mapping;

    public function __construct(BusFactoryInterface $busFactory = null)
    {
        $mapping = $busFactory->getDomainEvents();
        $this->mapping = reduce($this->eventsExtractor(), $mapping, []);
    }

    public function for(string $name)
    {
        if (!isset($this->mapping[$name])) {
            throw new RuntimeException("The Domain Event Class for <{$name}> doesn't exists or have no subscribers");
        }

        return $this->mapping[$name];
    }

    private function eventsExtractor(): callable
    {
        return fn (array $mapping, $subscriber): array => array_merge(
            $mapping,
            reindex($this->eventNameExtractor(), [$subscriber])
        );
    }

    /** @noinspection PhpUndefinedMethodInspection */
    private function eventNameExtractor(): callable
    {
        return static fn (string $eventClass): string => $eventClass::eventName();
    }
}
