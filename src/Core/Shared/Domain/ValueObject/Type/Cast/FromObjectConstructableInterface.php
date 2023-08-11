<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Cast;

interface FromObjectConstructableInterface
{
    /**
     * Try to create a new instance of the implementing class from given object
     * A ViolationExceptionInterface must be thrown when given object does not have a supported type.
     *
     * @return static
     */
    public static function fromObject(object $value): self;
}
