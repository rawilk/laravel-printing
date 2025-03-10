<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\PrintNode;
use Rawilk\Printing\Api\PrintNode\Resources\Printer as PrinterResource;
use Rawilk\Printing\Drivers\PrintNode\Entity\Printer;
use Rawilk\Printing\Drivers\PrintNode\Entity\PrintJob;

beforeEach(function () {
    Http::preventStrayRequests();
    PrintNode::setApiKey('my-key');

    $this->resource = PrinterResource::make(
        samplePrintNodeData('printer_single')[0],
    );
});

test('creates from api response', function () {
    $printer = new Printer($this->resource);

    expect($printer)
        ->id()->toBe(39)
        ->trays()->toEqualCanonicalizing(['Automatically Select'])
        ->isOnline()->toBeTrue()
        ->name()->toBe('Microsoft XPS Document Writer')
        ->description()->toBe('Microsoft XPS Document Writer')
        ->printer()->toBe($this->resource);
});

it('can be cast to array', function () {
    $printer = new Printer($this->resource);

    $expected = [
        'id' => 39,
        'name' => 'Microsoft XPS Document Writer',
        'description' => 'Microsoft XPS Document Writer',
        'online' => true,
        'status' => 'online',
        'trays' => [
            'Automatically Select',
        ],
        'capabilities' => $this->resource->capabilities->toArray(),
    ];

    expect($printer->toArray())->toEqualCanonicalizing($expected);
});

it('can fetch jobs that have been sent to it', function () {
    Http::fake([
        '/printers/39/printjobs' => Http::response(samplePrintNodeData('print_jobs')),
    ]);

    $printer = new Printer($this->resource);

    $jobs = $printer->jobs();

    expect($jobs)->toHaveCount(100)
        ->toContainOnlyInstancesOf(PrintJob::class);
});
