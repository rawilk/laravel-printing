<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Api\PrintNode\Requests\PrintJobRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

uses(PrintNodeTestCase::class);

test('can find a print job', function () {
    $this->fakeRequest('printjobs/473', 'print_job_single');

    $printJob = (new PrintJobRequest('1234'))->response(473);

    $this->assertNotNull($printJob);
    $this->assertInstanceOf(PrintJob::class, $printJob);
    $this->assertEquals(473, $printJob->id);
    $this->assertEquals('Print Job 1', $printJob->title);
    $this->assertEquals('pdf_uri', $printJob->contentType);
    $this->assertEquals('Google', $printJob->source);
    $this->assertEquals('deleted', $printJob->state);
});

test('can create a printer instance on the job', function () {
    $this->fakeRequest('printjobs/473', 'print_job_single');

    $printJob = (new PrintJobRequest('1234'))->response(473);

    $this->assertInstanceOf(Printer::class, $printJob->printer);
    $this->assertInstanceOf(Computer::class, $printJob->printer->computer);
    $this->assertSame(33, $printJob->printer->id);
    $this->assertSame(33, $printJob->printerId);
    $this->assertCount(0, $printJob->printer->trays());
});

test('returns null for no print job found', function () {
    $this->fakeRequest('printjobs/1234', 'print_job_single_not_found');

    $printJob = (new PrintJobRequest('1234'))->response(1234);

    $this->assertNull($printJob);
});
