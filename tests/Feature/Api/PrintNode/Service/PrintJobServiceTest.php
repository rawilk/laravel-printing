<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\Enums\ContentType;
use Rawilk\Printing\Api\PrintNode\PendingPrintJob;
use Rawilk\Printing\Api\PrintNode\PrintNodeClient;
use Rawilk\Printing\Api\PrintNode\Resources\Computer;
use Rawilk\Printing\Api\PrintNode\Resources\Printer;
use Rawilk\Printing\Api\PrintNode\Resources\PrintJob;
use Rawilk\Printing\Api\PrintNode\Resources\PrintJobState;
use Rawilk\Printing\Api\PrintNode\Service\PrintJobService;
use Rawilk\Printing\Exceptions\InvalidArgument;
use Rawilk\Printing\Exceptions\PrintTaskFailed;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\FakesPrintNodeRequests;

uses(FakesPrintNodeRequests::class);

beforeEach(function () {
    Http::preventStrayRequests();

    $client = new PrintNodeClient(['api_key' => 'my-key']);
    $this->service = new PrintJobService($client);
});

describe('single requests', function () {
    beforeEach(function () {
        $this->fakeRequests();
    });

    it('retrieves all print jobs', function () {
        $this->fakeRequest('print_jobs', expectation: function (Request $request) {
            expect($request->url())->toContain('/printjobs');
        });

        $response = $this->service->all();

        expect($response)->toHaveCount(100)
            ->toContainOnlyInstancesOf(PrintJob::class);
    });

    it('can limit results count', function () {
        $this->fakeRequest('print_jobs_limit', expectation: function (Request $request) {
            expect($request->url())->toContain('limit=3');
        });

        $response = $this->service->all(['limit' => 3]);

        expect($response)->toHaveCount(3);
    });

    it('can retrieve a print job', function () {
        $this->fakeRequest('print_job_single', expectation: function (Request $request) {
            expect($request->url())->toEndWith('/printjobs/473');
        });

        $job = $this->service->retrieve(473);

        expect($job)
            ->not->toBeNull()
            ->toBeInstanceOf(PrintJob::class)
            ->id->toBe(473)
            ->title->toBe('Print Job 1')
            ->contentType->toBe('pdf_uri')
            ->source->toBe('Google')
            ->state->toBe('deleted')
            ->printer->toBeInstanceOf(Printer::class)
            ->printer->computer->toBeInstanceOf(Computer::class);
    });

    it('returns null for no print job found', function () {
        $this->fakeRequest('print_job_single_not_found');

        $job = $this->service->retrieve(1234);

        expect($job)->toBeNull();
    });

    it('can retrieve a set of jobs', function () {
        $this->fakeRequest('print_jobs_set', expectation: function (Request $request) {
            expect($request->url())->toContain('/printjobs/473,474');
        });

        $response = $this->service->retrieveSet([473, 474]);

        expect($response)->toHaveCount(2);
    });

    test('retrieveSet() requires at least one id', function () {
        $this->service->retrieveSet([]);
    })->throws(InvalidArgument::class, 'At least one print job ID must be provided for this request.');

    it('retrieves the states for all print jobs', function () {
        $this->fakeRequest('print_job_states', expectation: function (Request $request) {
            expect($request->url())->toContain('/printjobs/states');
        });

        $response = $this->service->states();

        expect($response)
            ->toHaveCount(3)
            ->toContainOnlyInstancesOf(PrintJobState::class);
    });

    it('can retrieve print job states for a single job', function () {
        $this->fakeRequest('print_job_states_single', expectation: function (Request $request) {
            expect($request->url())->toContain('/printjobs/624/states');
        });

        $response = $this->service->statesFor(624);

        expect($response)->toHaveCount(2)
            ->first()->printJobId->toBe(624);
    });

    it('can cancel a print job', function () {
        $this->fakeRequest(
            callback: fn () => [1],
            expectation: function (Request $request) {
                expect($request->url())->toContain('/printjobs/1')
                    ->and($request->method())->toBe('DELETE');
            },
        );

        $response = $this->service->cancel(1);

        expect($response)->toBeArray()
            ->toEqualCanonicalizing([1]);
    });

    it('can cancel all pending print jobs', function () {
        $this->fakeRequest(
            callback: fn () => [1, 2],
            expectation: function (Request $request) {
                expect($request->method())->toBe('DELETE');
            },
        );

        $response = $this->service->cancelMany();

        expect($response)->toEqualCanonicalizing([1, 2]);
    });

    it('can cancel a set of jobs', function () {
        $this->fakeRequest(
            callback: fn () => [1, 2, 3],
            expectation: function (Request $request) {
                expect($request->url())->toContain('/printjobs/1,2,3');
            },
        );

        $response = $this->service->cancelMany([1, 2, 3]);

        expect($response)->toEqualCanonicalizing([1, 2, 3]);
    });
});

it('can create a new print job', function () {
    Http::fake([
        '/printjobs' => Http::response(473),
        '/printjobs/473' => Http::response(samplePrintNodeData('print_job_single')),
    ]);

    $pendingJob = PendingPrintJob::make()
        ->setContent('foo')
        ->setTitle('Print Job 1')
        ->setSource('Google')
        ->setPrinter(33);

    $printJob = $this->service->create($pendingJob);

    expect($printJob)->id->toBe(473);
});

it('can create a print job using an array for data', function () {
    Http::fake([
        '/printjobs' => Http::response(473),
        '/printjobs/473' => Http::response(samplePrintNodeData('print_job_single')),
    ]);

    $printJob = $this->service->create([
        'printerId' => 33,
        'contentType' => ContentType::RawBase64->value,
        'content' => base64_encode('foo'),
        'title' => 'Print Job 1',
        'source' => 'Google',
    ]);

    expect($printJob)->printer->id->toBe(33);
});

it('throws an exception if no print job is created', function () {
    Http::fake([
        '/printjobs' => Http::response([]),
    ]);

    $pendingJob = PendingPrintJob::make()
        ->setContent('foo')
        ->setTitle('Print Job 1')
        ->setSource('Google')
        ->setPrinter(33);

    $this->service->create($pendingJob);
})->throws(PrintTaskFailed::class, 'The print job failed to create');
