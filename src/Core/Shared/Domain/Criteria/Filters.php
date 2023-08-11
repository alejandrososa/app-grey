<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Criteria;

use App\Core\Shared\Domain\Collection;

use function Lambdish\Phunctional\reduce;

final class Filters extends Collection
{
    /**
     * @param array<Filter> $values
     */
    public static function fromValues(array $values): self
    {
        return new self(array_map(self::filterBuilder(), $values));
    }

    public function add(Filter $filter): self
    {
        return new self(array_merge($this->items(), [$filter]));
    }

    /**
     * @return array<Filter>
     */
    public function filters(): array
    {
        return $this->items();
    }

    public function serialize(): string
    {
        return reduce(
            fn (string $accumulate, Filter $filter): string => sprintf('%s^%s', $accumulate, $filter->serialize()),
            $this->items(),
            ''
        );
    }

    protected function type(): string
    {
        return Filter::class;
    }

    private static function filterBuilder(): callable
    {
        return fn (array $values): Filter => Filter::fromValues($values);
    }
}
