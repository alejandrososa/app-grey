<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain;

use function Lambdish\Phunctional\filter;

final class Utils
{
    public static function endsWith(string $needle, string $haystack): bool
    {
        $length = mb_strlen($needle);
        if ($length === 0) {
            return true;
        }

        return mb_substr($haystack, -$length) === $needle;
    }

    public static function dateToString(\DateTimeInterface $date): string
    {
        return $date->format(\DateTimeInterface::ATOM);
    }

    public static function stringToDate(string $date): \DateTimeImmutable
    {
        return new \DateTimeImmutable($date);
    }

    /** @param array<string, string> $values */
    public static function jsonEncode(array $values): string
    {
        return json_encode($values, JSON_THROW_ON_ERROR);
    }

    /** @return array<mixed> */
    public static function jsonDecode(string $json): array
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Unable to parse response body into JSON: ' . json_last_error());
        }

        return $data;
    }

    public static function toSnakeCase(string $text): string
    {
        return ctype_lower($text)
            ? $text : mb_strtolower(preg_replace('/([^A-Z\s])([A-Z])/', '$1_$2', $text));
    }

    public static function toCamelCase(string $text): string
    {
        return lcfirst(str_replace('_', '', ucwords($text, '_')));
    }

    /**
     * @param array<mixed> $array
     *
     * @return array<string>
     */
    public static function dot(array $array, string $prepend = ''): array
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (\is_array($value) && $value !== []) {
                $results = array_merge($results, static::dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /** @return array<string> */
    public static function filesIn(string $path, string $fileType): array
    {
        return filter(
            fn (string $possibleModule): string|bool => mb_strstr($possibleModule, $fileType),
            scandir($path)
        );
    }

    public static function extractClassName(object|string $object): string
    {
        $reflectionClass = new \ReflectionClass($object);

        return $reflectionClass->getShortName();
    }

    public static function extractClassNameArguments(object|string $object): ?string
    {
        $reflectionClass = new \ReflectionClass($object);

        $params = [];
        if ($reflectionClass->getConstructor()->getParameters()) {
            $params = $reflectionClass->getConstructor()->getParameters();
        }

        $args = [];
        foreach ($params as $param) {
            if ($param instanceof \ReflectionParameter && $param->getType() instanceof \ReflectionType) {
                $args[$param->getName()] = (string)$param->getType();
            }
        }

        return self::jsonEncode($args);
    }

    public static function extractContextAndSubDomain(object|string $object): string
    {
        $reflectionClass = new \ReflectionClass($object);

        $namespace = $reflectionClass->getNamespaceName();

        $context = explode('\\', $namespace)[1] ?? null;
        $subDomain = explode('\\', $namespace)[2] ?? null;

        return sprintf('%s\\%s', $context, $subDomain);
    }

    /**
     * @param array<mixed> $iterable
     *
     * @return array<mixed>
     */
    public static function iterableToArray(iterable $iterable): array
    {
        if (\is_array($iterable)) {
            return $iterable;
        }

        return iterator_to_array($iterable);
    }
}
