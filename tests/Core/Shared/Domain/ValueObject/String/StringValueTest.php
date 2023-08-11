<?php

declare(strict_types=1);

namespace App\Tests\Core\Shared\Domain\ValueObject\String;

use PHPUnit\Framework\TestCase;
use App\Core\Shared\Domain\ValueObject\String\StringValue;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToIntConvertibleInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToBoolConvertibleInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToArrayConvertibleInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToFloatConvertibleInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToStringConvertibleInterface;

class StringValueTest extends TestCase
{
    private string $value;
    private StringValue $stringValue;

    protected function setUp(): void
    {
        parent::setUp();
        $this->value = 'test';
        $this->stringValue = new StringValue('test');
    }

    public function testImplements(): void
    {
        self::assertInstanceOf(\JsonSerializable::class, $this->stringValue);
        self::assertNotInstanceOf(ToBoolConvertibleInterface::class, $this->stringValue);
        self::assertNotInstanceOf(ToIntConvertibleInterface::class, $this->stringValue);
        self::assertNotInstanceOf(ToFloatConvertibleInterface::class, $this->stringValue);
        self::assertInstanceOf(ToStringConvertibleInterface::class, $this->stringValue);
        self::assertNotInstanceOf(ToArrayConvertibleInterface::class, $this->stringValue);
    }

    public function testToString(): void
    {
        self::assertSame($this->value, (string)$this->stringValue);
    }

    public function testJsonSerialization(): void
    {
        self::assertSame('"' . $this->value . '"', \json_encode($this->stringValue));
    }

    public function testLength(): void
    {
        self::assertSame(mb_strlen($this->value), $this->stringValue->getLength());
    }
}
