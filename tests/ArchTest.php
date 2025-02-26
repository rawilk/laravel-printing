<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Facade;
use Rawilk\Printing\Api\Cups\AttributeGroup;
use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\PrintingServiceProvider;

arch()->preset()->security();

arch('strict types')->expect('Rawilk\Printing')->toUseStrictTypes();
arch('strict equality')->expect('Rawilk\Printing')->toUseStrictEquality();

arch('globals')->expect([
    'dd',
    'ddd',
    'dump',
    'env',
    'exit',
    'ray',

    // strict preset
    'sleep',
    'usleep',
])->not->toBeUsed();

arch('no final classes')
    ->expect('Rawilk\Printing')
    ->classes()
    ->not->toBeFinal()->ignoring([
        PrintingServiceProvider::class,
    ]);

arch('contracts')->expect('Rawilk\Printing\Contracts')
    ->not->toHaveSuffix('Interface')
    ->not->toHaveSuffix('Contract')
    ->toBeInterfaces();

arch('exceptions')->expect('Rawilk\Printing\Exceptions')
    ->not->toHaveSuffix('Exception')
    ->toExtend(Exception::class);

arch('facades')->expect('Rawilk\Printing\Facades')
    ->toExtend(Facade::class)
    ->not->toHaveSuffix('Facade');

describe('cups api', function (): void {
    arch('attributes')->expect('Rawilk\Printing\Api\Cups\Attributes')
        ->classes()
        ->toExtend(AttributeGroup::class);

    arch('enums')->expect('Rawilk\Printing\Api\Cups\Enums')
        ->toBeEnums()
        ->not->toHaveSuffix('Enum');

    arch('exceptions')->expect('Rawilk\Printing\Api\Cups\Exceptions')
        ->not->toHaveSuffix('Exception')
        ->toExtend(Exception::class);

    arch('types')->expect('Rawilk\Printing\Api\Cups\Types')
        ->classes()
        ->toExtend(Type::class);
});
