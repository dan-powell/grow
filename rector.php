<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/config',
        __DIR__.'/database',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withPhpVersion(80400) // PHP 8.4
    ->withSets([
        LevelSetList::UP_TO_PHP_84,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        // SetList::INSTANTIATION_TO_STATIC_CALL,
        SetList::PRIVATIZATION,
        SetList::STRICT_BOOLEANS,
    ])
    ->withSkip([
        // Skip rules that might cause issues
        \Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector::class,
        \Rector\Php80\Rector\FunctionLike\MixedTypeRector::class,
    ]);
// ->withImportShortClasses(false);
