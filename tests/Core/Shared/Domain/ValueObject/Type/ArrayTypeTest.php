<?php

declare(strict_types=1);

namespace App\Tests\Core\Shared\Domain\ValueObject\Type;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Core\Shared\Domain\ValueObject\Type\ArrayType;
use App\Core\Shared\Domain\ValueObject\Type\TypeViolation;
use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToArrayConvertibleInterface;

final class ArrayTypeTest extends TestCase
{
    private ArrayType $arrayType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->arrayType = new ArrayType();
    }

    public function testTypeExpectation(): void
    {
        $typeExpectation = $this->arrayType->getExpectation();
        self::assertFalse($typeExpectation->acceptsBool());
        self::assertFalse($typeExpectation->acceptsNull());
        self::assertFalse($typeExpectation->acceptsInt());
        self::assertFalse($typeExpectation->acceptsFloat());
        self::assertFalse($typeExpectation->acceptsString());
        self::assertTrue($typeExpectation->acceptsArray());
    }

    public function testArraySuccess(): void
    {
        self::assertSame(['value'], $this->arrayType->prepareValue(['value']));
    }

    public function testConvertibleToArraySuccess(): void
    {
        $input = new class() implements ToArrayConvertibleInterface {
            public function toArray(): array
            {
                return ['value'];
            }
        };
        self::assertSame(['value'], $this->arrayType->prepareValue($input));
    }

    public function testConvertibleToArrayWithoutInterfaceFails(): void
    {
        $input = new class() {
            public function toArray(): array
            {
                return ['value'];
            }
        };

        try {
            $this->arrayType->prepareValue($input);
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
        yield 'bool' => [true, false, 1];
        yield 'array' => [['value'], true, 0];
        yield 'object' => [(object)[], false, 1];
    }

    /**
     * @dataProvider typeProvider
     */
    public function testFailIfItIsNotArrayType(mixed $value, bool $expectedAssertion, int $expectedCountViolations): void
    {
        try {
            $this->arrayType->prepareValue($value);
            self::assertTrue($expectedAssertion, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount($expectedCountViolations, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }
}
