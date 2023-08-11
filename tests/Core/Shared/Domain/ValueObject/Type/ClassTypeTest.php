<?php

declare(strict_types=1);

namespace App\Tests\Core\Shared\Domain\ValueObject\Type;

use PHPUnit\Framework\TestCase;
use App\Core\Shared\Domain\ValueObject\Type\ClassType;
use App\Core\Shared\Domain\ValueObject\DateTime\DateTimeValue;

final class ClassTypeTest extends TestCase
{
    private ClassType $classType;
    private \DateTimeImmutable $dateTimeImmutable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dateTimeImmutable = \DateTimeImmutable::createFromFormat(
            \DateTimeInterface::ATOM,
            date(\DateTimeInterface::ATOM)
        );
        $this->classType = new ClassType(new \ReflectionClass(DateTimeValue::class));
    }

    public function testFromObjectConstructableOk(): void
    {
        $expectation = $this->classType->prepareValue($this->dateTimeImmutable);

        self::assertInstanceOf(DateTimeValue::class, $expectation);
        self::assertSame(date(\DateTimeInterface::ATOM), (string)$expectation);
    }
}
