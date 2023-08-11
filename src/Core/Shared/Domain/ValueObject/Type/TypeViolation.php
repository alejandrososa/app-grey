<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

use App\Core\Shared\Domain\ValueObject\Violation;
use App\Core\Shared\Domain\ValueObject\ViolationException;
use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;

final class TypeViolation extends Violation
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?: 'Unexpected value type.');
    }

    public static function exception(string $message = ''): ViolationExceptionInterface
    {
        return ViolationException::for(new self($message));
    }
}
