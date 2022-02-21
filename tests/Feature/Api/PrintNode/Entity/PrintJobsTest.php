<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Api\PrintNode\Entity\PrintJobs;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);

test('creates from an array of print job arrays', function () {
    $printJobs = (new PrintJobs)->setJobs(sampleData());

    $this->assertCount(100, $printJobs->jobs);
    $this->assertContainsOnlyInstancesOf(PrintJob::class, $printJobs->jobs);
});

// Helpers
function sampleData(): array
{
    return json_decode(
        file_get_contents(__DIR__ . '/../../../../stubs/Api/PrintNode/print_jobs.json'),
        true
    );
}
