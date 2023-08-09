<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;

abstract class Collection implements Countable, IteratorAggregate
{

    /** @param array<string, array> $items */
    public function __construct(private array $items)
    {
        Assert::arrayOf($this->type(), $items);
    }

    /** @return array<ArrayIterator> */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items());
    }

    public function count(): int
    {
        return \count($this->items());
    }

    abstract protected function type(): string;

    /** @return array<string, array> */
    protected function items(): array
    {
        return $this->items;
    }
}
