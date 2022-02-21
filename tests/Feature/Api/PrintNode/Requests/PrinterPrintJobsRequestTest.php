<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Api\PrintNode\Requests\PrinterPrintJobsRequest;

test('lists a printers print jobs', function () {
    $this->fakeRequest('printers/33/printjobs', 'printer_print_jobs');

    $response = (new PrinterPrintJobsRequest('1234'))->response(33);

    expect($response->jobs)->toHaveCount(7);
    $this->assertContainsOnlyInstancesOf(PrintJob::class, $response->jobs);

    $response->jobs->each(function (PrintJob $job) {
        expect($job->printerId)->toEqual(33);
    });
});
