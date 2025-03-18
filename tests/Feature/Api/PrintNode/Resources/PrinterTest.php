<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\PrintNode;
use Rawilk\Printing\Api\PrintNode\Resources\Computer;
use Rawilk\Printing\Api\PrintNode\Resources\Printer;
use Rawilk\Printing\Api\PrintNode\Resources\PrintJob;
use Rawilk\Printing\Api\PrintNode\Resources\Support\PrinterCapabilities;

beforeEach(function () {
    Http::preventStrayRequests();
    PrintNode::setApiKey('my-key');
});

test('printer data', function () {
    $printer = Printer::make(samplePrintNodeData('printer_single')[0]);

    expect($printer)
        ->id->toBe(39)
        ->computer->toBeInstanceOf(Computer::class)
        ->name->toBe('Microsoft XPS Document Writer')
        ->capabilities->toBeInstanceOf(PrinterCapabilities::class)
        ->default->toBeFalse()
        ->state->toBe('online')
        ->createdAt()->toBe(Date::parse('2015-11-17T13:02:37.224Z'))
        ->isOnline()->toBeTrue();
});

test('printer capabilities', function () {
    $printer = Printer::make(samplePrintNodeData('printer_single')[0]);

    expect($printer)
        ->copies()->toBe(1)
        ->isColor()->toBeTrue()
        ->canCollate()->toBeFalse()
        ->media()->toBe([])
        ->bins()->toEqualCanonicalizing(['Automatically Select']);
});

test('class url', function () {
    expect(Printer::classUrl())->toBe('/printers');
});

test('resource url', function () {
    expect(Printer::resourceUrl(123))->toBe('/printers/123');
});

test('instance url', function () {
    $printer = new Printer(450);

    expect($printer->instanceUrl())->toBe('/printers/450');
});

it('can refresh itself from the api', function () {
    $printer = new Printer(39);

    expect($printer)->not->toHaveKey('name');

    Http::fake([
        '/printers/39' => Http::response(samplePrintNodeData('printer_single')),
    ]);

    $printer->refresh();

    expect($printer)->toHaveKey('name')
        ->name->toBe('Microsoft XPS Document Writer')
        ->computer->toBeInstanceOf(Computer::class);
});

it('can be retrieved from the api', function () {
    Http::fake([
        '/printers/39' => Http::response(samplePrintNodeData('printer_single')),
    ]);

    $printer = Printer::retrieve(39);

    expect($printer)->id->toBe(39);
});

it('can retrieve all printers', function () {
    Http::fake([
        '/printers' => Http::response(samplePrintNodeData('printers')),
    ]);

    $printers = Printer::all();

    expect($printers)->toHaveCount(24)
        ->toContainOnlyInstancesOf(Printer::class);
});

test('retrieve all limit', function () {
    Http::fake([
        '/printers*' => Http::response(samplePrintNodeData('printers_limit')),
    ]);

    $printers = Printer::all(['limit' => 3]);

    expect($printers)->toHaveCount(3);

    Http::assertSent(function (Request $request) {
        expect($request->url())->toContain('limit=3');

        return true;
    });
});

it('can fetch its print jobs from the api', function () {
    Http::fake([
        '/printers/39/printjobs' => Http::response(samplePrintNodeData('print_jobs')),
    ]);

    $printer = new Printer(39);

    $jobs = $printer->printJobs();

    expect($jobs)->toHaveCount(100)
        ->toContainOnlyInstancesOf(PrintJob::class);
});

it('can fetch a specific print job from the api', function () {
    Http::fake([
        '/printers/39/printjobs/473' => Http::response(samplePrintNodeData('print_job_single')),
    ]);

    $printer = new Printer(39);

    $job = $printer->findPrintJob(473);

    expect($job)->toBeInstanceOf(PrintJob::class)
        ->id->toBe(473);
});

it('can fetch a set of print jobs from the api', function () {
    Http::fake([
        '/printers/39/printjobs/473,474' => Http::response(samplePrintNodeData('print_jobs_set')),
    ]);

    $printer = new Printer(39);

    $jobs = $printer->findPrintJob([473, 474]);

    expect($jobs)->toBeInstanceOf(Collection::class)
        ->toContainOnlyInstancesOf(PrintJob::class)
        ->toHaveCount(2);
});
