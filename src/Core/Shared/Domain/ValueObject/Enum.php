<?php

declare(strict_types=1);

namespace ApP\Core\Shared\Domain\ValueObject;

use function Lambdish\Phunctional\reindex;
use ApP\Core\Shared\Domain\Utils;
use ReflectionClass;
use Stringable;

abstract class Enum extends ValueObject implements Stringable
{
    protected static array $cache = [];

    public function __construct(protected $value)
    {
        $this->ensureIsBetweenAcceptedValues($value);
    }

    public static function __callStatic(string $name, $args)
    {
        return new static(self::values()[$name]);
    }

    public function __toString(): string
    {
        return (string) $this->value();
    }

    public static function fromString(string $value): Enum
    {
        return new static($value);
    }

    public static function values(): array
    {
        $class = static::class;

        if (!isset(self::$cache[$class])) {
            $reflectionClass = new ReflectionClass($class);
            self::$cache[$class] = reindex(self::keysFormatter(), $reflectionClass->getConstants());
        }

        return self::$cache[$class];
    }

    public static function randomValue()
    {
        return self::values()[array_rand(self::values())];
    }

    public static function random(): static
    {
        return new static(self::randomValue());
    }

    public function value()
    {
        return $this->value;
    }

    abstract protected function throwExceptionForInvalidValue($value);

    private static function keysFormatter(): callable
    {
        return static fn ($unused, string $key): string => Utils::toCamelCase(strtolower($key));
    }

    private function ensureIsBetweenAcceptedValues($value): void
    {
        if (!\in_array($value, static::values(), true)) {
            $this->throwExceptionForInvalidValue($value);
        }
    }
}
