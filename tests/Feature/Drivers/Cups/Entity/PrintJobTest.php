<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Resources\PrintJob as PrintJobResource;
use Rawilk\Printing\Drivers\Cups\Entity\PrintJob;

beforeEach(function () {
    $this->resource = PrintJobResource::make(baseCupsJobData());
});

it('creates from api resource', function () {
    $job = new PrintJob($this->resource);

    expect($job)
        ->id()->toBe('localhost:631/jobs/123')
        ->name()->toBe('my print job')
        ->printerId()->toBe('localhost:631/printers/TestPrinter')
        ->job()->toBe($this->resource)
        ->state()->toBe('completed');
});

it('can be cast to array', function () {
    $job = new PrintJob($this->resource);

    expect($job->toArray())->toEqualCanonicalizing([
        'id' => 'localhost:631/jobs/123',
        'date' => null,
        'name' => 'my print job',
        'printerId' => 'localhost:631/printers/TestPrinter',
        'printerName' => 'TestPrinter',
        'state' => 'completed',
    ]);
});
