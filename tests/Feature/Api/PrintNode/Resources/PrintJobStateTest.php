<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\PrintNode;
use Rawilk\Printing\Api\PrintNode\Resources\PrintJobState;

beforeEach(function () {
    Http::preventStrayRequests();
    PrintNode::setApiKey('my-key');
});

test('print job state data', function () {
    $state = PrintJobState::make([
        'printJobId' => 1,
        'state' => 'new',
        'message' => 'New job',
        'clientVersion' => null,
        'createTimestamp' => '2015-11-17T13:02:37.224Z',
    ]);

    expect($state)
        ->printJobId->toBe(1)
        ->state->toBe('new')
        ->message->toBe('New job')
        ->clientVersion->toBeNull()
        ->createdAt()->toBe(Date::parse('2015-11-17T13:02:37.224Z'));
});

test('class url', function () {
    expect(PrintJobState::classUrl())->toBe('/printjobs/states');
});

it('can retrieve all', function () {
    Http::fake([
        '/printjobs/states' => Http::response(samplePrintNodeData('print_job_states')),
    ]);

    $states = PrintJobState::all();

    expect($states)->toHaveCount(3)
        ->toContainOnlyInstancesOf(PrintJobState::class);
});
