<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Criteria;

use App\Core\Shared\Domain\ValueObject\String\StringValue;

/**
 * @method static OrderType asc()
 * @method static OrderType desc()
 * @method static OrderType none()
 */
final class OrderType extends StringValue
{
    public const ASC = 'asc';
    public const DESC = 'desc';
    public const NONE = 'none';

    public function isNone(): bool
    {
        return (string)$this === self::none();
    }

    protected function throwExceptionForInvalidValue(mixed $value): never
    {
        throw new \InvalidArgumentException($value);
    }
}
