<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\PrintNode as PrintNodeApi;
use Rawilk\Printing\Drivers\PrintNode\Entity\Printer;
use Rawilk\Printing\Drivers\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Drivers\PrintNode\PrintNode;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\FakesPrintNodeRequests;

uses(FakesPrintNodeRequests::class);

beforeEach(function () {
    Http::preventStrayRequests();

    $this->fakeRequests();

    $this->driver = new PrintNode('my-key');
});

it('lists an accounts printers', function () {
    $this->fakeRequest('printers');

    $printers = $this->driver->printers();

    expect($printers)->toHaveCount(24)
        ->toContainOnlyInstancesOf(Printer::class);
});

test('finds an accounts printer', function () {
    $this->fakeRequest('printer_single');

    $printer = $this->driver->printer(39);

    expect($printer)
        ->id()->toBe(39)
        ->trays()->toEqualCanonicalizing(['Automatically Select'])
        ->name()->toEqual('Microsoft XPS Document Writer')
        ->isOnline()->toBeTrue();
});

test('returns null for no printer found', function () {
    $this->fakeRequest('printer_single_not_found');

    $printer = $this->driver->printer(1234);

    expect($printer)->toBeNull();
});

it('lists all print jobs', function () {
    $this->fakeRequest('print_jobs_limit', expectation: function (Request $request) {
        expect($request->url())->toContain('limit=3');
    });

    $jobs = $this->driver->printJobs(limit: 3);

    expect($jobs)->toHaveCount(3)
        ->toContainOnlyInstancesOf(PrintJob::class);
});

it('retrieves a print job', function () {
    $this->fakeRequest('print_job_single', expectation: function (Request $request) {
        expect($request->url())->toContain('/printjobs/473');
    });

    $job = $this->driver->printJob(473);

    expect($job)->toBeInstanceOf(PrintJob::class)
        ->id()->toBe(473);
});

it('does not require an api key when a new instance is created', function () {
    $driver = new PrintNode;

    PrintNodeApi::setApiKey('global-key');

    $this->fakeRequest('printer_single');

    $printer = $driver->printer(39);

    $opts = invade($printer->printer())->_opts;

    expect($opts->apiKey)->toBe('global-key');
});

test('request options can be set when making api calls', function () {
    $this->fakeRequest('printer_single', expectation: function (Request $request) {
        expect($request->hasHeader('X-Idempotency-Key'))->toBeTrue()
            ->and($request->header('X-Idempotency-Key')[0])->toBe('foo');
    });

    $printer = $this->driver->printer(39, opts: ['idempotency_key' => 'foo', 'api_key' => 'other-key']);

    $opts = invade($printer->printer())->_opts;

    expect($opts->apiKey)->toBe('other-key');
});
