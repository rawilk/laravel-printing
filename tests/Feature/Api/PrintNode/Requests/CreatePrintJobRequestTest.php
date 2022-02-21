<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Api\PrintNode\Requests\CreatePrintJobRequest;
use Rawilk\Printing\Exceptions\PrintTaskFailed;

test('can create a print job', function () {
    Http::fake([
        'https://api.printnode.com/printjobs' => Http::response(473),
    ]);

    $this->fakeRequest('printjobs/473', 'print_job_single');

    $pendingJob = new PrintJob([
        'contentType' => 'pdf_uri',
        'content' => base64_encode('foo'),
        'title' => 'Print Job 1',
        'source' => 'Google',
        'options' => [],
    ]);
    $pendingJob->printerId = 33;

    $printJob = (new CreatePrintJobRequest('1234'))->send($pendingJob);

    expect($printJob->id)->toBe(473);
    expect($printJob->printer)->toBeInstanceOf(Printer::class);
    expect($printJob->printer->id)->toBe(33);
});

test('throws an exception if no job is created', function () {
    Http::fake([
        'https://api.printnode.com/printjobs' => Http::response(),
    ]);

    $this->expectException(PrintTaskFailed::class);
    $this->expectExceptionMessage('The print job failed to create.');

    $pendingJob = new PrintJob([
        'contentType' => 'pdf_uri',
        'content' => base64_encode('foo'),
        'title' => 'Print Job 1',
        'source' => 'Google',
        'options' => [],
    ]);
    $pendingJob->printerId = 33;

    (new CreatePrintJobRequest('1234'))->send($pendingJob);
});
