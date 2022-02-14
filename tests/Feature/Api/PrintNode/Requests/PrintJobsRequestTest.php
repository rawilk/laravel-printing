<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Api\PrintNode\Requests\PrintJobsRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

class PrintJobsRequestTest extends PrintNodeTestCase
{
    /** @test */
    public function lists_an_accounts_print_jobs(): void
    {
        $this->fakeRequest('printjobs', 'print_jobs');

        $response = (new PrintJobsRequest('1234'))->response();

        $this->assertCount(100, $response->jobs);
        $this->assertContainsOnlyInstancesOf(PrintJob::class, $response->jobs);
    }

    /** @test */
    public function can_limit_results_count(): void
    {
        $this->fakeRequest('printjobs*', 'print_jobs_limit');

        $response = (new PrintJobsRequest('1234'))->response(3);

        $this->assertCount(3, $response->jobs);
    }
}
