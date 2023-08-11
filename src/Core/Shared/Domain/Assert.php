<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class Assert
{
    /** @param array<string> $items */
    public static function arrayOf(string $class, array $items): void
    {
        foreach ($items as $item) {
            self::instanceOf($class, $item);
        }
    }

    public static function instanceOf(string $class, string $item): void
    {
        if (!$item instanceof $class) {
            try {
                $reflectionClass = new \ReflectionClass($item);
                $messageException = sprintf(
                    'The object <%s> is not an instance of <%s>',
                    $class,
                    $reflectionClass->getName()
                );
                throw new \InvalidArgumentException($messageException);
            } catch (\ReflectionException $e) {
                $message = sprintf('The object <%s> is not valid class', $item);
                throw new \InvalidArgumentException($message, $e->getCode(), $e);
            }
        }
    }

    public static function isValidEmailAddress(string $email): bool
    {
        try {
            return Assertion::email($email);
        } catch (AssertionFailedException $e) {
            return false;
        }
    }
}
