<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Stringable;
use Symfony\Component\Uid\Uuid as ComponentUuid;

class Uuid extends ValueObject implements Stringable
{
    public function __construct(protected string $value)
    {
        $this->ensureIsValidUuid($value);
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public static function fromString(string $value): static
    {
        return new static(ComponentUuid::fromString($value)->toRfc4122());
    }

    public static function random(): self
    {
        return new self(ComponentUuid::v4()->toRfc4122());
    }

    public function value(): string
    {
        return $this->value;
    }

    private function ensureIsValidUuid(string $id): void
    {
        if (!ComponentUuid::isValid($id)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $id));
        }
    }
}
