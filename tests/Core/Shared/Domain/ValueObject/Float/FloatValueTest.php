<?php

declare(strict_types=1);

namespace App\Tests\Core\Shared\Domain\ValueObject\Float;

use PHPUnit\Framework\TestCase;
use App\Core\Shared\Domain\ValueObject\Float\FloatValue;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToIntConvertibleInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToBoolConvertibleInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToArrayConvertibleInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToFloatConvertibleInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToStringConvertibleInterface;

class FloatValueTest extends TestCase
{
    private FloatValue $floatValue;

    protected function setUp(): void
    {
        $this->floatValue = new FloatValue(11.1);
        parent::setUp();
    }

    public function testImplements(): void
    {
        self::assertInstanceOf(\JsonSerializable::class, $this->floatValue);
        self::assertNotInstanceOf(ToBoolConvertibleInterface::class, $this->floatValue);
        self::assertNotInstanceOf(ToIntConvertibleInterface::class, $this->floatValue);
        self::assertInstanceOf(ToFloatConvertibleInterface::class, $this->floatValue);
        self::assertNotInstanceOf(ToStringConvertibleInterface::class, $this->floatValue);
        self::assertNotInstanceOf(ToArrayConvertibleInterface::class, $this->floatValue);
    }

    public function testToInt(): void
    {
        self::assertSame(11, $this->floatValue->toInt());
    }

    public function testToFloat(): void
    {
        self::assertSame(11.1, $this->floatValue->toFloat());
    }

    public function testToString(): void
    {
        self::assertSame('11.1', (string)$this->floatValue);
    }

    public function testJsonSerialization(): void
    {
        self::assertSame('11.1', \json_encode($this->floatValue));
    }
}
