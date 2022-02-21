<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Api\PrintNode\Entity\PrintJobs;

test('creates from an array of print job arrays', function () {
    $printJobs = (new PrintJobs)->setJobs(samplePrintNodeData('print_jobs'));

    expect($printJobs->jobs)->toHaveCount(100);
    $this->assertContainsOnlyInstancesOf(PrintJob::class, $printJobs->jobs);
});
