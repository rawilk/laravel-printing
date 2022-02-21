<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Requests\PrinterPrintJobRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

uses(PrintNodeTestCase::class);

test('can find a printers print job', function () {
    $this->fakeRequest('printers/33/printjobs/473', 'print_job_single');

    $printJob = (new PrinterPrintJobRequest('1234'))->response(33, 473);

    $this->assertNotNull($printJob);
    $this->assertSame(473, $printJob->id);
    $this->assertSame(33, $printJob->printer->id);
});

test('returns null for job not found', function () {
    $this->fakeRequest('printers/33/printjobs/1234', 'print_job_single_not_found');

    $printJob = (new PrinterPrintJobRequest('1234'))->response(33, 1234);

    $this->assertNull($printJob);
});
