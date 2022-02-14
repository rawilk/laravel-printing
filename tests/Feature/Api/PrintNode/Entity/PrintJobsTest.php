<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Entity;

use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Api\PrintNode\Entity\PrintJobs;
use Rawilk\Printing\Tests\TestCase;

class PrintJobsTest extends TestCase
{
    /** @test */
    public function creates_from_an_array_of_print_job_arrays(): void
    {
        $printJobs = (new PrintJobs)->setJobs($this->sampleData());

        $this->assertCount(100, $printJobs->jobs);
        $this->assertContainsOnlyInstancesOf(PrintJob::class, $printJobs->jobs);
    }

    protected function sampleData(): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../../../../stubs/Api/PrintNode/print_jobs.json'),
            true
        );
    }
}
