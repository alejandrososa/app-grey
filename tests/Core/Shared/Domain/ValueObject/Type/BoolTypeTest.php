<?php

declare(strict_types=1);

namespace App\Tests\Core\Shared\Domain\ValueObject\Type;

use PHPUnit\Framework\TestCase;
use App\Core\Shared\Domain\ValueObject\Type\BoolType;
use App\Core\Shared\Domain\ValueObject\Type\TypeViolation;
use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToBoolConvertibleInterface;

final class BoolTypeTest extends TestCase
{
    private BoolType $boolType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->boolType = new BoolType();
    }

    public function testTypeExpectation(): void
    {
        $typeExpectation = $this->boolType->getExpectation();
        self::assertTrue($typeExpectation->acceptsBool());
        self::assertFalse($typeExpectation->acceptsNull());
        self::assertFalse($typeExpectation->acceptsInt());
        self::assertFalse($typeExpectation->acceptsFloat());
        self::assertFalse($typeExpectation->acceptsString());
        self::assertFalse($typeExpectation->acceptsArray());
    }

    public function testBoolSuccess(): void
    {
        self::assertTrue($this->boolType->prepareValue(true));
    }

    public function testConvertibleToBoolSuccess(): void
    {
        $input = new class() implements ToBoolConvertibleInterface {
            public function toBool(): bool
            {
                return true;
            }
        };
        self::assertTrue($this->boolType->prepareValue($input));
    }

    public function testConvertibleToBoolWithoutInterfaceFails(): void
    {
        $input = new class() {
            public function toBool(): string
            {
                return true;
            }
        };

        try {
            $this->boolType->prepareValue($input);
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
        yield 'float' => [11.1, false, 1];
        yield 'bool' => [true, true, 0];
        yield 'array' => [[], false, 1];
        yield 'object' => [(object)[], false, 1];
    }

    /** @dataProvider typeProvider */
    public function testFailIfItIsNotBoolType(mixed $value, bool $expectedAssertion, int $expectedCountViolations): void
    {
        try {
            $this->boolType->prepareValue($value);
            self::assertTrue($expectedAssertion, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount($expectedCountViolations, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }
}
