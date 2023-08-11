<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject;

trait ImmutableObjectTrait
{
    /**
     * @throws \BadMethodCallException
     */
    final public function __set(string $name, mixed $value): void
    {
        throw new \BadMethodCallException('ImmutableObject cannot set undefined property.');
    }

    /**
     * @throws \BadMethodCallException
     */
    final public function __unset(string $name): void
    {
        throw new \BadMethodCallException('ImmutableObject cannot unset property.');
    }
}
