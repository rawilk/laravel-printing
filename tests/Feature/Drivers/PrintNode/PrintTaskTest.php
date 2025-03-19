<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Drivers\PrintNode\PrintNode;
use Rawilk\Printing\Exceptions\PrintTaskFailed;

beforeEach(function () {
    Http::preventStrayRequests();

    $this->driver = new PrintNode('my-key');
});

it('returns the print job id on a successful print job', function () {
    Http::fake([
        '/printjobs' => Http::response(473),
        '/printjobs/473' => Http::response(samplePrintNodeData('print_job_single')),
    ]);

    $job = $this->driver->newPrintTask()
        ->printer(33)
        ->content('foo')
        ->send();

    expect($job->id())->toEqual(473);
});

test('printer id is required', function () {
    $this->driver
        ->newPrintTask()
        ->content('foo')
        ->send();
})->throws(PrintTaskFailed::class, 'A printer must be specified');

test('print source is required', function () {
    $this->driver
        ->newPrintTask()
        ->printSource('')
        ->printer(33)
        ->content('foo')
        ->send();
})->throws(PrintTaskFailed::class, 'A print source must be specified');

test('content is required', function () {
    $this->driver
        ->newPrintTask()
        ->printer(33)
        ->send();
})->throws(PrintTaskFailed::class, 'No content was provided');

test('custom options can be sent through with api calls', function () {
    Http::fake([
        '/printjobs' => Http::response(473),
        '/printjobs/473' => Http::response(samplePrintNodeData('print_job_single')),
    ]);

    $job = $this->driver->newPrintTask()
        ->printer(33)
        ->content('foo')
        ->send([
            'api_key' => 'custom-key',
            'idempotency_key' => 'my_custom_key',
        ]);

    $opts = invade($job->job())->_opts;

    expect($opts)->apiKey->toBe('custom-key');

    Http::assertSent(function (Request $request) {
        if ($request->method() === 'POST') {
            expect($request->hasHeader('X-Idempotency-Key'))
                ->and($request->header('X-Idempotency-Key')[0])->toBe('my_custom_key');
        }

        return true;
    });
});
