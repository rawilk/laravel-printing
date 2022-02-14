<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Api\PrintNode\Requests\PrinterPrintJobsRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

class PrinterPrintJobsRequestTest extends PrintNodeTestCase
{
    /** @test */
    public function lists_a_printers_print_jobs(): void
    {
        $this->fakeRequest('printers/33/printjobs', 'printer_print_jobs');

        $response = (new PrinterPrintJobsRequest('1234'))->response(33);

        $this->assertCount(7, $response->jobs);
        $this->assertContainsOnlyInstancesOf(PrintJob::class, $response->jobs);

        $response->jobs->each(function (PrintJob $job) {
            $this->assertEquals(33, $job->printerId);
        });
    }
}
