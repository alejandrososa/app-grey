<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Criteria;

use App\Core\Shared\Domain\ValueObject\String\StringValue;

/**
 * @method static FilterOperator gt()
 * @method static FilterOperator lt()
 * @method static FilterOperator like()
 */
final class FilterOperator extends StringValue
{
    public const EQUAL = '=';
    public const NOT_EQUAL = '!=';
    public const GT = '>';
    public const LT = '<';
    public const CONTAINS = 'CONTAINS';
    public const NOT_CONTAINS = 'NOT_CONTAINS';

    /** @var array|string[] */
    private static array $containing = [self::CONTAINS, self::NOT_CONTAINS];

    public function isContaining(): bool
    {
        return \in_array((string)$this, self::$containing, true);
    }

    protected function throwExceptionForInvalidValue(string $value): never
    {
        throw new \InvalidArgumentException(sprintf('The filter <%s> is invalid', $value));
    }
}
