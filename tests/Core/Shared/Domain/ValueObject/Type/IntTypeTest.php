<?php

declare(strict_types=1);

namespace App\Tests\Core\Shared\Domain\ValueObject\Type;

use PHPUnit\Framework\TestCase;
use App\Core\Shared\Domain\ValueObject\Type\IntType;
use App\Core\Shared\Domain\ValueObject\Type\TypeViolation;
use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToIntConvertibleInterface;

final class IntTypeTest extends TestCase
{
    private IntType $intType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->intType = new IntType();
    }

    public function testTypeExpectation(): void
    {
        $typeExpectation = $this->intType->getExpectation();
        self::assertFalse($typeExpectation->acceptsFloat());
        self::assertFalse($typeExpectation->acceptsNull());
        self::assertTrue($typeExpectation->acceptsInt());
        self::assertFalse($typeExpectation->acceptsBool());
        self::assertFalse($typeExpectation->acceptsString());
        self::assertFalse($typeExpectation->acceptsArray());
    }

    public function testIntTypeSuccess(): void
    {
        self::assertSame(10, $this->intType->prepareValue(10));
    }

    public function testConvertibleToIntTypeSuccess(): void
    {
        $input = new class() implements ToIntConvertibleInterface {
            public function toInt(): int
            {
                return 10;
            }
        };
        self::assertSame(10, $this->intType->prepareValue($input));
    }

    public function testConvertibleToIntTypeWithoutInterfaceFails(): void
    {
        $input = new class() {
            public function toInt(): int
            {
                return 10;
            }
        };

        try {
            $this->intType->prepareValue($input);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }

    public static function typeProvider(): \Iterator
    {
        yield 'string' => ['hi world', false, 1];
        yield 'null' => [null, false, 1];
        yield 'int' => [10, true, 0];
        yield 'float' => [11.1, false, 1];
        yield 'bool' => [true, false, 1];
        yield 'array' => [[], false, 1];
        yield 'object' => [(object)[], false, 1];
    }

    /** @dataProvider typeProvider */
    public function testFailIfItIsNotIntType(mixed $value, bool $expectedAssertion, int $expectedCountViolations): void
    {
        try {
            $this->intType->prepareValue($value);
            self::assertTrue($expectedAssertion, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount($expectedCountViolations, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }
}
