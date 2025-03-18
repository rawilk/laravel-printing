<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\JobState;
use Rawilk\Printing\Api\Cups\Resources\PrintJob;

it('creates from response data', function () {
    $job = PrintJob::make(baseCupsJobData());

    expect($job)
        ->uri->toBe('localhost:631/jobs/123')
        ->jobUri->toBe('localhost:631/jobs/123')
        ->jobPrinterUri->toBe('localhost:631/printers/TestPrinter')
        ->jobName->toBe('my print job')
        ->jobState->toBe(JobState::Completed->value);
});
