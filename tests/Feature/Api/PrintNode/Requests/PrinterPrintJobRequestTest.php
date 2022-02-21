<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Requests\PrinterPrintJobRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

uses(PrintNodeTestCase::class);

test('can find a printers print job', function () {
    $this->fakeRequest('printers/33/printjobs/473', 'print_job_single');

    $printJob = (new PrinterPrintJobRequest('1234'))->response(33, 473);

    $this->assertNotNull($printJob);
    expect($printJob->id)->toBe(473);
    expect($printJob->printer->id)->toBe(33);
});

test('returns null for job not found', function () {
    $this->fakeRequest('printers/33/printjobs/1234', 'print_job_single_not_found');

    $printJob = (new PrinterPrintJobRequest('1234'))->response(33, 1234);

    expect($printJob)->toBeNull();
});
