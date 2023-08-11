<?php

declare(strict_types=1);

namespace App\Tests\Core\Shared\Domain\ValueObject\Type;

use PHPUnit\Framework\TestCase;
use App\Core\Shared\Domain\ValueObject\Type\StringType;
use App\Core\Shared\Domain\ValueObject\Type\TypeViolation;
use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToStringConvertibleInterface;

final class StringTypeTest extends TestCase
{
    private StringType $stringType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stringType = new StringType();
    }

    public function testTypeExpectation(): void
    {
        $typeExpectation = $this->stringType->getExpectation();
        self::assertTrue($typeExpectation->acceptsString());
        self::assertFalse($typeExpectation->acceptsNull());
        self::assertFalse($typeExpectation->acceptsInt());
        self::assertFalse($typeExpectation->acceptsFloat());
        self::assertFalse($typeExpectation->acceptsBool());
        self::assertFalse($typeExpectation->acceptsArray());
    }

    public function testStringSuccess(): void
    {
        self::assertSame('test', $this->stringType->prepareValue('test'));
    }

    public function testConvertibleToStringSuccess(): void
    {
        $input = new class() implements ToStringConvertibleInterface {
            public function __toString(): string
            {
                return 'test';
            }
        };
        self::assertSame('test', $this->stringType->prepareValue($input));
    }

    public function testConvertibleToStringWithoutInterfaceFails(): void
    {
        $input = new class() {
            public function __toString(): string
            {
                return 'test';
            }
        };

        try {
            $this->stringType->prepareValue($input);
            self::assertTrue(false, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount(1, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }

    public static function typeProvider(): \Iterator
    {
        yield 'string' => ['hi world', true, 0];
        yield 'null' => [null, false, 1];
        yield 'int' => [10, false, 1];
        yield 'float' => [11.1, false, 1];
        yield 'bool' => [true, false, 1];
        yield 'array' => [[], false, 1];
        yield 'object' => [(object)[], false, 1];
    }

    /** @dataProvider typeProvider */
    public function testFailIfItIsNotStringType(mixed $value, bool $expectedAssertion, int $expectedCountViolations): void
    {
        try {
            $this->stringType->prepareValue($value);
            self::assertTrue($expectedAssertion, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount($expectedCountViolations, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }
}
