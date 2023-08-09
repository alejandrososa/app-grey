<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Query;

use App\Core\Shared\Domain\Bus\Query\Query;
use RuntimeException;

final class QueryNotRegisteredError extends RuntimeException
{
    public function __construct(Query $query)
    {
        $queryClass = \get_class($query);

        parent::__construct("The query <{$queryClass}> hasn't a query handler associated");
    }
}
