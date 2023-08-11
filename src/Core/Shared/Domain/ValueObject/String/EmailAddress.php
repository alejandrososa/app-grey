<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\String;

use App\Core\Shared\Domain\Assert;

class EmailAddress extends StringValue
{
    public function __construct(string $value)
    {
        if (Assert::isValidEmailAddress($value)) {
        }
        parent::__construct($value);
    }
}
