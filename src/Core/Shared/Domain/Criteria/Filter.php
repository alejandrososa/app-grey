<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Criteria;

final class Filter
{
    public function __construct(
        private FilterField $filterField,
        private FilterOperator $filterOperator,
        private FilterValue $filterValue
    ) {
    }

    public static function fromValues(array $values): self
    {
        return new self(
            new FilterField($values['field']),
            new FilterOperator($values['operator']),
            new FilterValue($values['value'])
        );
    }

    public function field(): FilterField
    {
        return $this->filterField;
    }

    public function operator(): FilterOperator
    {
        return $this->filterOperator;
    }

    public function value(): FilterValue
    {
        return $this->filterValue;
    }

    public function serialize(): string
    {
        return sprintf('%s.%s.%s', $this->filterField->value(), $this->filterOperator->value(), $this->filterValue->value());
    }
}
