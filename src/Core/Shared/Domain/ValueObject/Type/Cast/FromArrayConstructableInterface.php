<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Cast;

interface FromArrayConstructableInterface
{
    /**
     * @param array<mixed> $value
     *
     * @return static
     */
    public static function fromArray(array $value): self;
}
