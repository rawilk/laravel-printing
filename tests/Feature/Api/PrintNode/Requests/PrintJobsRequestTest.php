<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Api\PrintNode\Requests\PrintJobsRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

uses(PrintNodeTestCase::class);

test('lists an accounts print jobs', function () {
    $this->fakeRequest('printjobs', 'print_jobs');

    $response = (new PrintJobsRequest('1234'))->response();

    expect($response->jobs)->toHaveCount(100);
    $this->assertContainsOnlyInstancesOf(PrintJob::class, $response->jobs);
});

test('can limit results count', function () {
    $this->fakeRequest('printjobs*', 'print_jobs_limit');

    $response = (new PrintJobsRequest('1234'))->response(3);

    expect($response->jobs)->toHaveCount(3);
});
