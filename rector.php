<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitLevelSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SensiolabsSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return static function (RectorConfig $rectorConfig): void {
    // get parameters
    $parameters = $rectorConfig->parameters();
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');

    // basic rules
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses();
    $rectorConfig->removeUnusedImports();

    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_82);

    // register single rule
    $rectorConfig->rule(TypedPropertyFromAssignsRector::class);
    $rectorConfig->rule(TypedPropertyFromStrictConstructorRector::class);

    $rectorConfig->symfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml');

    // here we can define, what sets of rules will be applied
    // tip: use "SetList" class to autocomplete sets with your IDE
    $rectorConfig->sets([
        SetList::PHP_82,
        SetList::CODE_QUALITY,
        SetList::NAMING,
        SetList::TYPE_DECLARATION,
        SetList::DEAD_CODE,
    ]);

    // symfony rules
    $rectorConfig->sets([
        SymfonySetList::SYMFONY_63,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        SensiolabsSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ]);

    // doctrine rules
    $rectorConfig->sets([
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
    ]);

    // phpunit rules
    $rectorConfig->sets([
        PHPUnitSetList::PHPUNIT_100,
        PHPUnitLevelSetList::UP_TO_PHPUNIT_100,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
    ]);

    $rectorConfig->indent(' ', 4);
};
