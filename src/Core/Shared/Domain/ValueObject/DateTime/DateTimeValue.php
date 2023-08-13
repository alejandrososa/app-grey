<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\DateTime;

use App\Core\Shared\Domain\ValueObject\Type\TypeViolation;
use App\Core\Shared\Domain\ValueObject\ViolationException;
use App\Core\Shared\Domain\ValueObject\ImmutableObjectTrait;
use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToStringConvertibleInterface;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\DateTime\DateTimeValueTest
 */
class DateTimeValue implements
    \JsonSerializable,
    ToStringConvertibleInterface,
    FromDateTimeImmutableConstructableInterface
{
    use ImmutableObjectTrait;

    protected const FORMAT = \DATE_ATOM;
    protected const TIMEZONE = 'UTC';

    private \DateTimeImmutable $value;

    final private function __construct(\DateTimeImmutable $dateTimeImmutable)
    {
        $this->value = $dateTimeImmutable;
    }

    private static function timezone(): \DateTimeZone
    {
        return new \DateTimeZone((string)static::TIMEZONE);
    }

    /**
     * @throws ViolationExceptionInterface
     *
     * @return static
     */
    public static function fromString(string $value): self
    {
        try {
            $dateTimeImmutable = new \DateTimeImmutable($value, self::timezone());
        } catch (\Exception $e) {
            throw DateTimeFormatViolation::exception((string)static::FORMAT);
        }

        return new static($dateTimeImmutable);
    }

    /**
     * @throws ViolationExceptionInterface
     *
     * @return static
     */
    final public static function fromFormat(string $format, string $value): self
    {
        $dateTime = \DateTimeImmutable::createFromFormat($format, $value);

        if ($dateTime === false) {
            throw DateTimeFormatViolation::exception($format);
        }

        return new static($dateTime);
    }

    final public static function fromDateTimeImmutable(\DateTimeImmutable $dateTimeImmutable): self
    {
        if ($dateTimeImmutable->getTimezone()->getName() !== 'UTC') {
            $dateTimeImmutable = $dateTimeImmutable->setTimezone(self::timezone());
        }

        return new static($dateTimeImmutable);
    }

    final public static function fromDateTime(\DateTime $dateTime): self
    {
        $immutable = \DateTimeImmutable::createFromMutable($dateTime);

        return static::fromDateTimeImmutable($immutable);
    }

    final public static function fromDateTimeInterface(\DateTimeInterface $value): self
    {
        $dateTime = new \DateTimeImmutable('now', self::timezone());
        $dateTime = $dateTime->setTimestamp($value->getTimestamp());

        return new static($dateTime);
    }

    /**
     * @psalm-suppress LessSpecificImplementedReturnType
     *
     * @throws ViolationExceptionInterface
     */
    public static function fromObject(object $value): self
    {
        if ($value instanceof \DateTimeImmutable) {
            return static::fromDateTimeImmutable($value);
        }

        if ($value instanceof \DateTime) {
            return static::fromDateTime($value);
        }

        if ($value instanceof \DateTimeInterface) {
            return static::fromDateTimeInterface($value);
        }

        throw ViolationException::for(new TypeViolation());
    }

    /**
     * @psalm-suppress InvalidToString
     */
    final public function __toString(): string
    {
        return $this->value->format((string)static::FORMAT);
    }

    final public function jsonSerialize(): string
    {
        return (string)$this;
    }

    final public function toDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->value;
    }

    final public function getOffset(): int
    {
        return $this->value->getOffset();
    }

    final public function getTimestamp(): int
    {
        return $this->value->getTimestamp();
    }

    final public function setTimestamp(int $timestamp): self
    {
        return new static($this->value->setTimestamp($timestamp));
    }

    final public function getTimezone(): \DateTimeZone
    {
        return $this->value->getTimezone();
    }

    final public function format(string $format): string
    {
        return $this->value->format($format);
    }

    final public function diff(\DateTimeInterface|DateTimeValue $datetime2): \DateInterval
    {
        if ($datetime2 instanceof self) {
            $datetime2 = $datetime2->toDateTimeImmutable();
        } elseif (!$datetime2 instanceof \DateTimeInterface) {
            /* @psalm-suppress DocblockTypeContradiction check in runtime too */
            throw new \InvalidArgumentException();
        }

        return $this->value->diff($datetime2);
    }

    final public function modify(string $modifier): self
    {
        return new static($this->value->modify($modifier));
    }

    final public function add(\DateInterval $dateInterval): self
    {
        return new static($this->value->add($dateInterval));
    }

    final public function sub(\DateInterval $dateInterval): self
    {
        return new static($this->value->sub($dateInterval));
    }
}
