<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Float;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\Float\BoundedFloatTest
 */
abstract class BoundedFloat extends FloatValue
{
    abstract protected static function minValue(): float;

    abstract protected static function maxValue(): float;

    public function __construct(float $value)
    {
        $minValue = static::minValue();
        $maxValue = static::maxValue();
        FloatOutOfBounds::check($minValue, $maxValue, $value);
        parent::__construct($value);
    }
}
