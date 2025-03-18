<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Rawilk\Printing\Api\PrintNode\PendingPrintJob;
use Rawilk\Printing\Api\PrintNode\PrintNode;
use Rawilk\Printing\Api\PrintNode\Resources\Printer;
use Rawilk\Printing\Api\PrintNode\Resources\PrintJob;
use Rawilk\Printing\Api\PrintNode\Resources\PrintJobState;
use Rawilk\Printing\Exceptions\PrintTaskFailed;

beforeEach(function () {
    Http::preventStrayRequests();
    PrintNode::setApiKey('my-key');
});

afterEach(function () {
    Str::createUuidsNormally();
});

test('print job data', function () {
    $job = PrintJob::make(samplePrintNodeData('print_job_single')[0]);

    expect($job)
        ->id->toBe(473)
        ->printer->toBeInstanceOf(Printer::class)
        ->title->toBe('Print Job 1')
        ->source->toBe('Google')
        ->state->toBe('deleted')
        ->createdAt()->toBe(Date::parse('2015-11-16T23:14:12.354Z'))
        ->expiresAt()->toBeNull();
});

test('class url', function () {
    expect(PrintJob::classUrl())->toBe('/printjobs');
});

test('resource url', function () {
    expect(PrintJob::resourceUrl(123))->toBe('/printjobs/123');
});

test('instance url', function () {
    $job = new PrintJob(1000);

    expect($job->instanceUrl())->toBe('/printjobs/1000');
});

it('can refresh itself from the api', function () {
    $job = new PrintJob(473);

    expect($job)->not->toHaveKey('title');

    Http::fake([
        '/printjobs/473' => Http::response(samplePrintNodeData('print_job_single')),
    ]);

    $job->refresh();

    expect($job)->toHaveKey('title')
        ->title->toBe('Print Job 1');
});

it('can be retrieved from the api', function () {
    Http::fake([
        '/printjobs/473' => Http::response(samplePrintNodeData('print_job_single')),
    ]);

    $job = PrintJob::retrieve(473);

    expect($job)->id->toBe(473);
});

it('can retrieve all print jobs', function () {
    Http::fake([
        '/printjobs' => Http::response(samplePrintNodeData('print_jobs')),
    ]);

    $jobs = PrintJob::all();

    expect($jobs)->toHaveCount(100)
        ->toContainOnlyInstancesOf(PrintJob::class);
});

test('retrieve all limit', function () {
    Http::fake([
        '/printjobs*' => Http::response(samplePrintNodeData('print_jobs_limit')),
    ]);

    $jobs = PrintJob::all(['limit' => 3]);

    expect($jobs)->toHaveCount(3);

    Http::assertSent(function (Request $request) {
        expect($request->url())->toContain('limit=3');

        return true;
    });
});

it('can cancel itself', function () {
    $job = new PrintJob(473);

    Http::fake([
        '/printjobs/473' => Http::response([473]),
    ]);

    $job->cancel();

    Http::assertSent(function (Request $request) {
        expect($request->method())->toBe('DELETE')
            ->and($request->url())->toContain('/printjobs/473');

        return true;
    });
});

it('can fetch all of its states from the api', function () {
    Http::fake([
        '/printjobs/473/states' => Http::response(samplePrintNodeData('print_job_states_single')),
    ]);

    $job = new PrintJob(473);

    $states = $job->getStates();

    expect($states)->toHaveCount(2)
        ->toContainOnlyInstancesOf(PrintJobState::class);
});

it('can create a new print job', function () {
    Str::createUuidsUsing(fn () => 'foo');

    Http::fake([
        '/printjobs' => Http::response(473),
        '/printjobs/473' => Http::response(samplePrintNodeData('print_job_single')),
    ]);

    $pendingJob = PendingPrintJob::make()
        ->setContent('foo')
        ->setTitle('Print Job 1')
        ->setSource('Google')
        ->setPrinter(33);

    $printJob = PrintJob::create($pendingJob);

    expect($printJob)->id->toBe(473);

    Http::assertSent(function (Request $request) {
        if ($request->method() === 'POST') {
            expect($request->hasHeader('X-Idempotency-Key'))->toBeTrue()
                ->and($request->header('X-Idempotency-Key')[0])->toBe('foo');
        }

        return true;
    });
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

    PrintJob::create($pendingJob);
})->throws(PrintTaskFailed::class, 'The print job failed to create');
