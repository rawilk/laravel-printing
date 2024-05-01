<?php

declare(strict_types=1);

test('can get the job id', function () {
    expect(createCupsJob()->id())->toBe('localhost:631/jobs/123');
});

test('can get the job name', function () {
    expect(createCupsJob()->name())->toEqual('my print job');
});

test('can get the job state', function () {
    expect(createCupsJob()->state())->toEqual('completed');
});

test('can get the printer name and id', function () {
    $job = createCupsJob();

    expect($job->printerName())->toEqual('printer-name');
    expect($job->printerId())->toEqual('localhost:631/printers/printer-name');
});
