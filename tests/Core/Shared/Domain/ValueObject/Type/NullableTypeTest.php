<?php

declare(strict_types=1);

namespace App\Tests\Core\Shared\Domain\ValueObject\Type;

use PHPUnit\Framework\TestCase;
use App\Core\Shared\Domain\ValueObject\Type\NullableType;
use App\Core\Shared\Domain\ValueObject\Type\TypeInterface;
use App\Core\Shared\Domain\ValueObject\Type\NullableTypeExpectation;

final class NullableTypeTest extends TestCase
{
    private NullableType $nullableType;
    private TypeInterface $mixedType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mixedType = self::createMock(TypeInterface::class);
        $this->nullableType = new NullableType($this->mixedType);
    }

    public function testCreateNullableExpectation(): void
    {
        $typeExpectation = $this->nullableType->getExpectation();
        self::assertInstanceOf(NullableTypeExpectation::class, $typeExpectation);
    }

    public function testNullSuccess(): void
    {
        self::assertNull($this->nullableType->prepareValue(null));
    }

    public static function typeProvider(): \Iterator
    {
        yield 'string' => ['hi world', null];
        yield 'null' => [null, null];
        yield 'int' => [10, null];
        yield 'float' => [11.1, null];
        yield 'bool' => [true, null];
        yield 'array' => [[], null];
        yield 'object' => [(object)[], null];
    }

    /** @dataProvider typeProvider */
    public function testNotAcceptsOtherTypes(mixed $value, mixed $expectedValue): void
    {
        self::assertSame($expectedValue, $this->nullableType->prepareValue($value));
    }
}
