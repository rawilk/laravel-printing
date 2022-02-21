<?php

declare(strict_types=1);

use Carbon\Carbon;
use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;

test('can be created from array', function () {
    $job = new PrintJob(samplePrintNodeData('print_job_single')[0]);

    expect($job->id)->toBe(473);
    expect($job->printer)->toBeInstanceOf(Printer::class);
    expect($job->printerId)->toBe(33);
    expect($job->printer->id)->toBe(33);
    expect($job->title)->toEqual('Print Job 1');
    expect($job->contentType)->toEqual('pdf_uri');
    expect($job->source)->toEqual('Google');
    expect($job->state)->toEqual('deleted');
    expect($job->created)->toBeInstanceOf(Carbon::class);
    expect($job->created->format('Y-m-d H:i:s'))->toEqual('2015-11-16 23:14:12');
});

test('casts to array', function () {
    $data = samplePrintNodeData('print_job_single')[0];
    $job = new PrintJob($data);

    $asArray = $job->toArray();

    foreach ($data as $key => $value) {
        // Not supported at this time
        if ($key === 'expireAt') {
            continue;
        }

        $this->assertArrayHasKey($key, $asArray);
    }

    // Computer & printer should be cast to arrays as well.
    expect($asArray['printer'])->toBeArray();
    expect($asArray['printer']['computer'])->toBeArray();

    // 'createTimestamp' is a custom key added by the printer's toArray() method.
    $this->assertArrayHasKey('createTimestamp', $asArray['printer']);
});
