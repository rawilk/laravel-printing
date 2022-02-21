<?php

declare(strict_types=1);

use Rawilk\Printing\Drivers\Cups\Entity\Printer;
use Rawilk\Printing\Drivers\Cups\Entity\PrintJob;
use Rawilk\Printing\Drivers\Cups\Support\Client;
use Rawilk\Printing\Tests\TestCase;
use Smalot\Cups\Builder\Builder;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Model\Job;
use Smalot\Cups\Model\Printer as CupsPrinter;
use Smalot\Cups\Transport\ResponseParser;

uses(TestCase::class);

beforeEach(function () {
    $client = new Client;
    $responseParser = new ResponseParser;
    $builder = new Builder;

    $this->jobManager = new JobManager($builder, $client, $responseParser);
});

test('can get the job id', function () {
    expect(createJob()->id())->toBe(123456);
});

test('can get the job name', function () {
    expect(createJob()->name())->toEqual('my print job');
});

test('can get the job state', function () {
    expect(createJob()->state())->toEqual('success');
});

test('can get the printer name and id', function () {
    $job = createJob();

    expect($job->printerName())->toEqual('printer-name');
    expect($job->printerId())->toEqual('localhost:631');
});

// Helpers
function createJob(): PrintJob
{
    $cupsJob = new Job;
    $cupsJob->setId(123456)
        ->setName('my print job')
        ->setState('success');

    return new PrintJob($cupsJob, test()->createPrinter());
}

function createPrinter(): Printer
{
    $cupsPrinter = new CupsPrinter;
    $cupsPrinter->setName('printer-name')
        ->setUri('localhost:631')
        ->setStatus('online');

    return new Printer($cupsPrinter, test()->jobManager);
}
