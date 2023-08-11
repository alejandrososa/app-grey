<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain;

/** @phpstan-consistent-constructor */
abstract class Collection implements \Countable, \IteratorAggregate
{
    /** @param array<mixed> $items */
    public function __construct(private array $items)
    {
        Assert::arrayOf($this->type(), $items);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items());
    }

    public function count(): int
    {
        return count($this->items());
    }

    abstract protected function type(): string;

    /** @return array<mixed> */
    protected function items(): array
    {
        return $this->items;
    }
}
