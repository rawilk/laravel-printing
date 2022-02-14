<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Requests\PrinterPrintJobRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

class PrinterPrintJobRequestTest extends PrintNodeTestCase
{
    /** @test */
    public function can_find_a_printers_print_job(): void
    {
        $this->fakeRequest('printers/33/printjobs/473', 'print_job_single');

        $printJob = (new PrinterPrintJobRequest('1234'))->response(33, 473);

        $this->assertNotNull($printJob);
        $this->assertSame(473, $printJob->id);
        $this->assertSame(33, $printJob->printer->id);
    }

    /** @test */
    public function returns_null_for_job_not_found(): void
    {
        $this->fakeRequest('printers/33/printjobs/1234','print_job_single_not_found');

        $printJob = (new PrinterPrintJobRequest('1234'))->response(33, 1234);

        $this->assertNull($printJob);
    }
}
