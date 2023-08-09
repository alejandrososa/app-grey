<?php

namespace App\Core\Shared\Domain\ValueObject;

use DateTime;
use DomainException;
use Assert\Assertion;
use Assert\AssertionFailedException;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Stringable;

abstract class DateTimeObject extends ValueObject implements Stringable
{
    public const FORMAT_FULL = 'Y-m-d H:i:s';
    public const FORMAT_SHORT = 'Y-m-d';
    public const FORMAT_SHORT_SLASH = 'd/m/Y';

    public function __toString(): string
    {
        return $this->value() ?? '';
    }

    public function shortFormat(): static
    {
        $date = self::create($this->value)->format(self::FORMAT_SHORT);

        return new static($date);
    }

    public function shortFormatWithSlash(): static
    {
        $date = self::create($this->value)->format(self::FORMAT_SHORT_SLASH);

        return new static($date);
    }

    public function fullFormat(): static
    {
        $date = self::create($this->value)->format(self::FORMAT_FULL);

        return new static($date);
    }

    public function timestamp(): int
    {
        return self::create($this->value)->getTimestamp();
    }

    public function dateTime(): DateTimeImmutable
    {
        return self::create($this->value);
    }

    public function modifyDate(DateInterval $dateInterval): static
    {
        $date = self::create($this->value);
        $date->add($dateInterval);

        return new static($date->format(self::FORMAT_FULL));
    }

    public static function fromString(
        ?string $date = 'now',
        ?string $format = self::FORMAT_FULL,
        ?DateTimeZone $dateTimeZone = null
    ): static {
        self::guardFormatValidation($date);

        $date = self::create($date, $format, $dateTimeZone)->format(self::FORMAT_FULL);

        return new static($date);
    }

    private static function create(
        string $time,
        ?string $format = self::FORMAT_FULL,
        ?DateTimeZone $dateTimeZone = null
    ): DateTimeImmutable {
        return $format
            ? DateTimeImmutable::createFromFormat($format, $time, $dateTimeZone)
            : new DateTimeImmutable($time, $dateTimeZone);
    }

    public static function fromDateTime(DateTime $dateTime): static
    {
        $dateTime = $dateTime->format(self::FORMAT_FULL);

        return new static($dateTime);
    }

    public static function today(): static
    {
        $date = (new DateTime())->format(self::FORMAT_FULL);

        return new static($date);
    }

    public function value(): ?string
    {
        if ($this->value === null || $this->value === '') {
            return null;
        }

        return $this->value;
    }

    public static function guardFormatValidation(?string $date, string $format = self::FORMAT_FULL)
    {
        if ($date !== null && $date !== '') {
            try {
                $date = 'now' === $date ? date($format) : $date;
                Assertion::date($date, $format, 'invalid date');
            } catch (AssertionFailedException $e) {
                throw new DomainException($e->getMessage(), $e->getCode(), $e);
            }
        }
    }
}
