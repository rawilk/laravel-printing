<?php

declare(strict_types=1);

use Rawilk\Printing\Drivers\Cups\Support\Client;
use Smalot\Cups\Builder\Builder;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Transport\ResponseParser;

beforeEach(function () {
    $client = new Client;
    $responseParser = new ResponseParser;
    $builder = new Builder;

    $this->jobManager = new JobManager($builder, $client, $responseParser);
});

test('can get the job id', function () {
    expect(createCupsJob()->id())->toBe(123456);
});

test('can get the job name', function () {
    expect(createCupsJob()->name())->toEqual('my print job');
});

test('can get the job state', function () {
    expect(createCupsJob()->state())->toEqual('success');
});

test('can get the printer name and id', function () {
    $job = createCupsJob();

    expect($job->printerName())->toEqual('printer-name');
    expect($job->printerId())->toEqual('localhost:631');
});
