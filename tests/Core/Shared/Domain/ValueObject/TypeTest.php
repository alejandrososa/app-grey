<?php

declare(strict_types=1);

namespace App\Tests\Core\Shared\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use App\Core\Shared\Domain\ValueObject\Type;
use App\Core\Shared\Domain\ValueObject\Type\IntType;
use App\Core\Shared\Domain\ValueObject\Type\BoolType;
use App\Core\Shared\Domain\ValueObject\Type\ArrayType;
use App\Core\Shared\Domain\ValueObject\Type\FloatType;
use App\Core\Shared\Domain\ValueObject\Type\StringType;

class TypeTest extends TestCase
{
    public static function typeProvider(): \Iterator
    {
        yield 'string' => ['string', StringType::class];
        yield 'int' => ['int', IntType::class];
        yield 'float' => ['float', FloatType::class];
        yield 'bool' => ['bool', BoolType::class];
        yield 'array' => ['array', ArrayType::class];
    }

    /** @dataProvider typeProvider */
    public function testFailIfItIsNotStringType(mixed $expectedType, string $class): void
    {
        self::assertInstanceOf($class, Type::forBuiltinType($expectedType));
    }
}
