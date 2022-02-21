<?php

declare(strict_types=1);

use Carbon\Carbon;
use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);

test('can be created from array', function () {
    $job = new PrintJob(sampleData());

    $this->assertSame(473, $job->id);
    $this->assertInstanceOf(Printer::class, $job->printer);
    $this->assertSame(33, $job->printerId);
    $this->assertSame(33, $job->printer->id);
    $this->assertEquals('Print Job 1', $job->title);
    $this->assertEquals('pdf_uri', $job->contentType);
    $this->assertEquals('Google', $job->source);
    $this->assertEquals('deleted', $job->state);
    $this->assertInstanceOf(Carbon::class, $job->created);
    $this->assertEquals('2015-11-16 23:14:12', $job->created->format('Y-m-d H:i:s'));
});

test('casts to array', function () {
    $data = sampleData();
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
    $this->assertIsArray($asArray['printer']);
    $this->assertIsArray($asArray['printer']['computer']);

    // 'createTimestamp' is a custom key added by the printer's toArray() method.
    $this->assertArrayHasKey('createTimestamp', $asArray['printer']);
});

// Helpers
function sampleData(): array
{
    return json_decode(
        file_get_contents(__DIR__ . '/../../../../stubs/Api/PrintNode/print_job_single.json'),
        true
    )[0];
}
