<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Api\PrintNode\Requests\PrinterPrintJobsRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

uses(PrintNodeTestCase::class);

test('lists a printers print jobs', function () {
    $this->fakeRequest('printers/33/printjobs', 'printer_print_jobs');

    $response = (new PrinterPrintJobsRequest('1234'))->response(33);

    $this->assertCount(7, $response->jobs);
    $this->assertContainsOnlyInstancesOf(PrintJob::class, $response->jobs);

    $response->jobs->each(function (PrintJob $job) {
        $this->assertEquals(33, $job->printerId);
    });
});
