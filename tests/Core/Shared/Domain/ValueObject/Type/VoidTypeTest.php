<?php

declare(strict_types=1);

namespace App\Tests\Core\Shared\Domain\ValueObject\Type;

use PHPUnit\Framework\TestCase;
use App\Core\Shared\Domain\ValueObject\Type\VoidType;
use App\Core\Shared\Domain\ValueObject\Type\TypeViolation;
use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;

final class VoidTypeTest extends TestCase
{
    private VoidType $voidType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->voidType = new VoidType();
    }

    public function testTypeExpectation(): void
    {
        $typeExpectation = $this->voidType->getExpectation();
        self::assertFalse($typeExpectation->acceptsString());
        self::assertFalse($typeExpectation->acceptsNull());
        self::assertFalse($typeExpectation->acceptsInt());
        self::assertFalse($typeExpectation->acceptsFloat());
        self::assertFalse($typeExpectation->acceptsBool());
        self::assertFalse($typeExpectation->acceptsArray());
    }

    public static function typeProvider(): \Iterator
    {
        yield 'string' => ['', false, 1];
        yield 'null' => [null, false, 1];
        yield 'int' => [10, false, 1];
        yield 'float' => [11.1, false, 1];
        yield 'bool' => [true, false, 1];
        yield 'array' => [[], false, 1];
        yield 'object' => [(object)[], false, 1];
    }

    /** @dataProvider typeProvider */
    public function testFailIfItIsNotTheSameType(mixed $value, bool $expectedAssertion, int $expectedCountViolations): void
    {
        try {
            $this->voidType->prepareValue($value);
            self::assertTrue($expectedAssertion, 'Exception not thrown.');
        } catch (ViolationExceptionInterface $e) {
            $violations = $e->getViolations();
            self::assertCount($expectedCountViolations, $violations);
            self::assertInstanceOf(TypeViolation::class, \reset($violations));
        }
    }
}
