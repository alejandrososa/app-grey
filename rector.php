<?php

use Rector\Config\RectorConfig;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return static function (RectorConfig $rectorConfig): void {
    // get parameters
    $parameters = $rectorConfig->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__.'/src',
    ]);
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_82);

    // register single rule
    $rectorConfig->rule(TypedPropertyFromAssignsRector::class);
    $rectorConfig->rule(TypedPropertyFromStrictConstructorRector::class);

    // here we can define, what sets of rules will be applied
    // tip: use "SetList" class to autocomplete sets with your IDE
    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
    ]);

    $rectorConfig->sets([SetList::PHP_82]);
    $rectorConfig->paths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ]);

    // $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');
    $rectorConfig->indent(' ', 4);
};
