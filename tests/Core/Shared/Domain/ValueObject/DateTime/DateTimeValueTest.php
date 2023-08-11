<?php

declare(strict_types=1);

namespace App\Tests\Core\Shared\Domain\ValueObject\DateTime;

use PHPUnit\Framework\TestCase;
use App\Core\Shared\Domain\ValueObject\DateTime\DateTimeValue;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToStringConvertibleInterface;
use App\Core\Shared\Domain\ValueObject\DateTime\FromDateTimeImmutableConstructableInterface;

class DateTimeValueTest extends TestCase
{
    public function testImplements(): void
    {
        $dateTimeValue = DateTimeValue::fromDateTimeImmutable(new \DateTimeImmutable());
        self::assertInstanceOf(\JsonSerializable::class, $dateTimeValue);
        self::assertInstanceOf(ToStringConvertibleInterface::class, $dateTimeValue);
        self::assertInstanceOf(FromDateTimeImmutableConstructableInterface::class, $dateTimeValue);
    }

    public function testFromString(): void
    {
        $dateTimeValue = DateTimeValue::fromString('2020-05-26 13:14:15');
        self::assertSame('2020-05-26T13:14:15+00:00', (string)$dateTimeValue);
        self::assertSame('UTC', $dateTimeValue->getTimezone()->getName());
    }

    public function testFromImmutable(): void
    {
        $dateTimeImmutable = new \DateTimeImmutable('2020-05-26 13:14:15', new \DateTimeZone('Europe/Prague'));
        $value = DateTimeValue::fromDateTimeImmutable($dateTimeImmutable);
        self::assertSame($dateTimeImmutable->getTimestamp(), $value->getTimestamp());
        self::assertSame('UTC', $value->getTimezone()->getName());
    }

    public function testFromImmutableAsObject(): void
    {
        $dateTimeImmutable = new \DateTimeImmutable('2020-05-26 13:14:15', new \DateTimeZone('Europe/Prague'));
        $value = DateTimeValue::fromObject($dateTimeImmutable);
        self::assertSame($dateTimeImmutable->getTimestamp(), $value->getTimestamp());
        self::assertSame('UTC', $value->getTimezone()->getName());
    }

    public function testFromMutable(): void
    {
        $dateTime = new \DateTime('2020-05-26 13:14:15', new \DateTimeZone('Europe/Prague'));
        $value = DateTimeValue::fromDateTime($dateTime);
        self::assertSame($dateTime->getTimestamp(), $value->getTimestamp());
        self::assertSame('UTC', $value->getTimezone()->getName());
    }

    public function testFromMutableAsObject(): void
    {
        $dateTime = new \DateTime('2020-05-26 13:14:15', new \DateTimeZone('Europe/Prague'));
        $value = DateTimeValue::fromObject($dateTime);
        self::assertSame($dateTime->getTimestamp(), $value->getTimestamp());
        self::assertSame('UTC', $value->getTimezone()->getName());
    }

    public function testFromFormat(): void
    {
        $dateTimeValue = DateTimeValue::fromFormat('d.m.Y H:i:s', '26.5.2020 13:14:15');
        self::assertSame('2020-05-26T13:14:15+00:00', (string)$dateTimeValue);
        self::assertSame('UTC', $dateTimeValue->getTimezone()->getName());
    }

    public function testJsonSerialization(): void
    {
        $dateTimeValue = DateTimeValue::fromString('2020-05-26 13:14:15');
        self::assertSame('"2020-05-26T13:14:15+00:00"', \json_encode($dateTimeValue));
    }
}
