<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Resources\Printer as PrinterResource;
use Rawilk\Printing\Api\Cups\Types\Primitive\Keyword;
use Rawilk\Printing\Drivers\Cups\Entity\Printer;

beforeEach(function () {
    $this->resource = PrinterResource::make(baseCupsPrinterData());
});

test('creates from api response', function () {
    $printer = new Printer($this->resource);

    expect($printer)
        ->id()->toBe('localhost:631')
        ->isOnline()->toBeTrue()
        ->name()->toBe('TestPrinter')
        ->printer()->toBe($this->resource);
});

test('can be cast to array', function () {
    $printer = new Printer($this->resource);

    $expected = [
        'id' => 'localhost:631',
        'name' => 'TestPrinter',
        'description' => null,
        'online' => true,
        'status' => 'Idle',
        'trays' => [],
        'capabilities' => [
            'media-source-supported' => [],
            'printer-state-reasons' => [],
        ],
    ];

    expect($printer->toArray())->toEqualCanonicalizing($expected);
});

it('can get the printer trays', function () {
    $printer = new Printer(PrinterResource::make([
        ...baseCupsPrinterData(),
        'media-source-supported' => new Keyword(['Tray 1']),
    ]));

    expect($printer->trays())->toEqualCanonicalizing(['Tray 1']);
});
