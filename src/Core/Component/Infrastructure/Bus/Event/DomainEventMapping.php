<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Event;

use App\Core\Component\Infrastructure\Bus\BusFactoryInterface;

use function Lambdish\Phunctional\reduce;
use function Lambdish\Phunctional\reindex;

final class DomainEventMapping
{
    /**
     * @var array<mixed>
     */
    private array $mapping;

    public function __construct(BusFactoryInterface $busFactory = null)
    {
        $mapping = $busFactory->getDomainEvents();
        $this->mapping = reduce($this->eventsExtractor(), $mapping, []);
    }

    public function for(string $name): mixed
    {
        if (!isset($this->mapping[$name])) {
            $message = "The Domain Event Class for <{$name}> doesn't exists or have no subscribers";
            throw new \RuntimeException($message);
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
