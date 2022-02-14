<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Requests;

use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Api\PrintNode\Requests\CreatePrintJobRequest;
use Rawilk\Printing\Exceptions\PrintTaskFailed;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

class CreatePrintJobRequestTest extends PrintNodeTestCase
{
    /** @test */
    public function can_create_a_print_job(): void
    {
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

        $this->assertSame(473, $printJob->id);
        $this->assertInstanceOf(Printer::class, $printJob->printer);
        $this->assertSame(33, $printJob->printer->id);
    }

    /** @test */
    public function throws_an_exception_if_no_job_is_created(): void
    {
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
    }
}
