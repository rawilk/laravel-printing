<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Facade;
use Rawilk\Printing\Api\Cups\AttributeGroup;
use Rawilk\Printing\Api\Cups\Cups;
use Rawilk\Printing\Api\Cups\CupsObject;
use Rawilk\Printing\Api\Cups\Service\AbstractService as CupsAbstractService;
use Rawilk\Printing\Api\Cups\Service\ServiceFactory as CupsServiceFactory;
use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\Api\PrintNode\PrintNode;
use Rawilk\Printing\Api\PrintNode\PrintNodeApiResource;
use Rawilk\Printing\Api\PrintNode\PrintNodeObject;
use Rawilk\Printing\Api\PrintNode\Service\AbstractService as PrintNodeAbstractService;
use Rawilk\Printing\Api\PrintNode\Service\ServiceFactory as PrintNodeServiceFactory;
use Rawilk\Printing\Exceptions\ExceptionInterface;
use Rawilk\Printing\Exceptions\PrintingException;
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
        PrintNode::class,
        Cups::class,
    ]);

arch('contracts')->expect('Rawilk\Printing\Contracts')
    ->not->toHaveSuffix('Interface')
    ->not->toHaveSuffix('Contract')
    ->toBeInterfaces();

arch('enums')->expect('Rawilk\Printing\Enums')
    ->toBeEnums()
    ->not->toHaveSuffix('Enum');

arch('exceptions')->expect('Rawilk\Printing\Exceptions')
    ->classes()
    ->not->toHaveSuffix('Exception')->ignoring([
        PrintingException::class,
    ])
    ->toExtend(Throwable::class)
    ->toImplement(ExceptionInterface::class);

arch('facades')->expect('Rawilk\Printing\Facades')
    ->toExtend(Facade::class)
    ->not->toHaveSuffix('Facade');

arch('concerns')->expect('Rawilk\Printing\Concerns')
    ->toBeTraits();

describe('cups api', function (): void {
    arch('attributes')->expect('Rawilk\Printing\Api\Cups\Attributes')
        ->classes()
        ->toExtend(AttributeGroup::class);

    arch('enums')->expect('Rawilk\Printing\Api\Cups\Enums')
        ->toBeEnums()
        ->not->toHaveSuffix('Enum');

    arch('exceptions')->expect('Rawilk\Printing\Api\Cups\Exceptions')
        ->not->toHaveSuffix('Exception')
        ->toExtend(PrintingException::class)
        ->toOnlyBeUsedIn('Rawilk\Printing\Api\Cups');

    arch('types')->expect('Rawilk\Printing\Api\Cups\Types')
        ->classes()
        ->toExtend(Type::class);

    arch('resources')->expect('Rawilk\Printing\Api\Cups\Resources')
        ->classes()
        ->toExtend(CupsObject::class);

    arch('services')->expect('Rawilk\Printing\Api\Cups\Service')
        ->classes()
        ->toExtend(CupsAbstractService::class)->ignoring([CupsServiceFactory::class])
        ->toHaveSuffix('Service')->ignoring([CupsServiceFactory::class]);
});

describe('printnode api', function () {
    arch('enums')->expect('Rawilk\Printing\Api\PrintNode\Enums')
        ->toBeEnums()
        ->not->toHaveSuffix('Enum');

    arch('exceptions')->expect('Rawilk\Printing\Api\PrintNode\Exceptions')
        ->toImplement(ExceptionInterface::class)
        ->not->toHaveSuffix('Exception');

    arch('resources')->expect('Rawilk\Printing\Api\PrintNode\Resources')
        ->classes()
        ->toExtend(PrintNodeObject::class);

    arch('resource concerns')->expect('Rawilk\Printing\Api\PrintNode\Resources\Concerns')
        ->toBeTraits()
        ->toOnlyBeUsedIn('Rawilk\Printing\Api\PrintNode\Resources');

    arch('resource operations')->expect('Rawilk\Printing\Api\PrintNode\Resources\ApiOperations')
        ->toBeTraits()
        ->toOnlyBeUsedIn([
            'Rawilk\Printing\Api\PrintNode\Resources',
            PrintNodeApiResource::class,
        ]);

    arch('services')->expect('Rawilk\Printing\Api\PrintNode\Service')
        ->classes()
        ->toExtend(PrintNodeAbstractService::class)->ignoring([PrintNodeServiceFactory::class])
        ->toHaveSuffix('Service')->ignoring([PrintNodeServiceFactory::class]);
});
