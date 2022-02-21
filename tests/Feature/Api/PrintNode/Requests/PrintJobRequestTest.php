<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Api\PrintNode\Requests\PrintJobRequest;

test('can find a print job', function () {
    $this->fakeRequest('printjobs/473', 'print_job_single');

    $printJob = (new PrintJobRequest('1234'))->response(473);

    $this->assertNotNull($printJob);
    expect($printJob)->toBeInstanceOf(PrintJob::class);
    expect($printJob->id)->toEqual(473);
    expect($printJob->title)->toEqual('Print Job 1');
    expect($printJob->contentType)->toEqual('pdf_uri');
    expect($printJob->source)->toEqual('Google');
    expect($printJob->state)->toEqual('deleted');
});

test('can create a printer instance on the job', function () {
    $this->fakeRequest('printjobs/473', 'print_job_single');

    $printJob = (new PrintJobRequest('1234'))->response(473);

    expect($printJob->printer)->toBeInstanceOf(Printer::class);
    expect($printJob->printer->computer)->toBeInstanceOf(Computer::class);
    expect($printJob->printer->id)->toBe(33);
    expect($printJob->printerId)->toBe(33);
    expect($printJob->printer->trays())->toHaveCount(0);
});

test('returns null for no print job found', function () {
    $this->fakeRequest('printjobs/1234', 'print_job_single_not_found');

    $printJob = (new PrintJobRequest('1234'))->response(1234);

    expect($printJob)->toBeNull();
});
