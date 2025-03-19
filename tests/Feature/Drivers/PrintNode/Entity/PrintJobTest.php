<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Date;
use Rawilk\Printing\Api\PrintNode\Resources\PrintJob as PrintJobResource;
use Rawilk\Printing\Drivers\PrintNode\Entity\PrintJob;

beforeEach(function () {
    $this->resource = PrintJobResource::make(
        samplePrintNodeData('print_job_single')[0]
    );
});

it('creates from api resource', function () {
    $job = new PrintJob($this->resource);

    expect($job)
        ->id()->toBe(473)
        ->date()->toBe(Date::parse('2015-11-16T23:14:12.354Z'))
        ->name()->toBe('Print Job 1')
        ->printerId()->toBe(33)
        ->state()->toBe('deleted')
        ->job()->toBe($this->resource);
});

it('can be cast to array', function () {
    $job = new PrintJob($this->resource);

    $expected = [
        'id' => 473,
        'date' => Date::parse('2015-11-16T23:14:12.354Z'),
        'name' => 'Print Job 1',
        'printerId' => 33,
        'printerName' => 'Printer 1',
        'state' => 'deleted',
    ];

    expect($job->toArray())->toMatchArray($expected);
});
