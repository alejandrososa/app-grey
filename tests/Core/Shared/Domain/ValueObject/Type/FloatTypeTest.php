<?php

declare(strict_types=1);

namespace App\Tests\Core\Shared\Domain\ValueObject\Type;

use PHPUnit\Framework\TestCase;
use App\Core\Shared\Domain\ValueObject\Type\FloatType;
use App\Core\Shared\Domain\ValueObject\Type\TypeViolation;
use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToFloatConvertibleInterface;

final class FloatTypeTest extends TestCase
{
    private FloatType $floatType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->floatType = new FloatType();
    }

    public function testTypeExpectation(): void
    {
        $typeExpectation = $this->floatType->getExpectation();
        self::assertTrue($typeExpectation->acceptsFloat());
        self::assertFalse($typeExpectation->acceptsNull());
        self::assertFalse($typeExpectation->acceptsInt());
        self::assertFalse($typeExpectation->acceptsBool());
        self::assertFalse($typeExpectation->acceptsString());
        self::assertFalse($typeExpectation->acceptsArray());
    }

    public function testFloatTypeSuccess(): void
    {
        self::assertSame(11.1, $this->floatType->prepareValue(11.1));
    }

    public function testConvertibleToFloatTypeSuccess(): void
    {
        $input = new class() implements ToFloatConvertibleInterface {
            public function toFloat(): float
            {
                return 11.1;
            }
        };
        self::assertSame(11.1, $this->floatType->prepareValue($input));
    }

    public function testConvertibleToFloatTypeWithoutInterfaceFails(): void
    {
        $input = new class() {
            public function toBool(): float
            {
                return 11.1;
            }
        };

        try {
            $this->floatType->prepareValue($input);
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
        yield 'int' => [10, false, 1];
        yield 'float' => [11.1, true, 0];
        yield 'bool' => [true, false, 1];
        yield 'array' => [[], false, 1];
        yield 'object' => [(object)[], false, 1];
    }

    /** @dataProvider typeProvider */
    public function testFailIfItIsNotFloatType(mixed $value, bool $expectedAssertion, int $expectedCountViolations): void
    {
        try {
            $this->floatType->prepareValue($value);
            self::assertTrue($expectedAssertion, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount($expectedCountViolations, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }
}
