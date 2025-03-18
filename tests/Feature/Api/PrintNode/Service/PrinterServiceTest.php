<?php

declare(strict_types=1);

use Carbon\CarbonInterface;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\PrintNodeClient;
use Rawilk\Printing\Api\PrintNode\Resources\Computer;
use Rawilk\Printing\Api\PrintNode\Resources\Printer;
use Rawilk\Printing\Api\PrintNode\Resources\PrintJob;
use Rawilk\Printing\Api\PrintNode\Resources\Support\PrinterCapabilities;
use Rawilk\Printing\Api\PrintNode\Service\PrinterService;
use Rawilk\Printing\Exceptions\InvalidArgument;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\FakesPrintNodeRequests;

uses(FakesPrintNodeRequests::class);

beforeEach(function () {
    Http::preventStrayRequests();

    $this->fakeRequests();

    $client = new PrintNodeClient(['api_key' => 'my-key']);
    $this->service = new PrinterService($client);
});

it('retrieves all printers', function () {
    $this->fakeRequest('printers');

    $response = $this->service->all();

    expect($response)->toHaveCount(24)
        ->toContainOnlyInstancesOf(Printer::class);
});

it('can limit results count', function () {
    $this->fakeRequest('printers_limit', expectation: function (Request $request) {
        expect($request->url())->toContain('limit=3');
    });

    $response = $this->service->all(['limit' => 3]);

    expect($response)->toHaveCount(3);
});

it('can retrieve a printer by id', function () {
    $this->fakeRequest('printer_single', expectation: function (Request $request) {
        expect($request->url())->toEndWith('/printers/39');
    });

    $printer = $this->service->retrieve(39);

    expect($printer)
        ->not->toBeNull()
        ->id->toBe(39)
        ->computer->toBeInstanceOf(Computer::class)
        ->name->toBe('Microsoft XPS Document Writer')
        ->description->toBe('Microsoft XPS Document Writer')
        ->capabilities->toBeInstanceOf(PrinterCapabilities::class)
        ->trays()->toEqualCanonicalizing(['Automatically Select'])
        ->createdAt()->toBeInstanceOf(CarbonInterface::class)
        ->createdAt()->toBe(Date::parse('2015-11-17T13:02:37.224Z'))
        ->state->toBe('online')
        ->isOnline()->toBeTrue()
        ->canCollate()->toBeFalse()
        ->isColor()->toBeTrue()
        ->copies()->toBe(1);
});

test('a printer knows if printnode says it is offline', function () {
    $this->fakeRequest('printer_single_offline');

    $printer = $this->service->retrieve(40);

    expect($printer)->isOnline()->toBeFalse();
});

it('handles a printer with no capabilities reported', function () {
    $this->fakeRequest('printer_single_no_capabilities');

    $printer = $this->service->retrieve(34);

    expect($printer)
        ->capabilities->toBeNull()
        ->trays()->toBeArray()
        ->trays()->toBeEmpty();
});

test('retrieve returns null for printer not found', function () {
    $this->fakeRequest('printer_single_not_found');

    $printer = $this->service->retrieve(1234);

    expect($printer)->toBeNull();
});

it('can list the print jobs for a printer', function () {
    $this->fakeRequest('printer_print_jobs');

    $response = $this->service->printJobs(33);

    expect($response)->toHaveCount(7)
        ->toContainOnlyInstancesOf(PrintJob::class);

    $response->each(function (PrintJob $job) {
        expect($job->printer)->not->toBeNull()
            ->and($job->printer->id)->toBe(33);
    });
});

it('can retrieve a specific print job for a printer', function () {
    $this->fakeRequest('print_job_single', expectation: function (Request $request) {
        expect($request->url())->toEndWith('/printers/33/printjobs/473');
    });

    $job = $this->service->printJob(33, 473);

    expect($job)->not->toBeNull()
        ->id->toBe(473)
        ->printer->id->toBe(33);
});

it('returns null for a print job not found for a printer', function () {
    $this->fakeRequest('print_job_single_not_found');

    $job = $this->service->printJob(33, 1234);

    expect($job)->toBeNull();
});

it('can retrieve a set of printers', function () {
    $this->fakeRequest('printer_set', expectation: function (Request $request) {
        expect($request->url())->toContain('/printers/34,36');
    });

    $response = $this->service->retrieveSet([34, 36]);

    expect($response)->toHaveCount(2);
});

test('retrieveSet() requires at least one id', function () {
    $this->service->retrieveSet([]);
})->throws(InvalidArgument::class, 'At least one printer ID must be provided for this request.');
