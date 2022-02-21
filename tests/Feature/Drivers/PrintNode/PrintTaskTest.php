<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Drivers\PrintNode\PrintNode;
use Rawilk\Printing\Exceptions\PrintTaskFailed;
use Rawilk\Printing\Tests\Concerns\FakesPrintNodeRequests;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);
uses(FakesPrintNodeRequests::class);

beforeEach(function () {
    $this->printNode = new PrintNode;
});

it('returns the print job id on a successful print job', function () {
    Http::fake([
        'https://api.printnode.com/printjobs' => Http::response(473),
    ]);

    $this->fakeRequest('printjobs/473', 'print_job_single');

    $job = $this->printNode
        ->newPrintTask()
        ->printer(33)
        ->content('foo')
        ->send();

    $this->assertEquals(473, $job->id());
});

test('printer id is required', function () {
    $this->expectException(PrintTaskFailed::class);
    $this->expectExceptionMessage('A printer must be specified to print!');

    $this->printNode
        ->newPrintTask()
        ->content('foo')
        ->send();
});

test('print source is required', function () {
    $this->expectException(PrintTaskFailed::class);
    $this->expectExceptionMessage('A print source must be specified!');

    $this->printNode
        ->newPrintTask()
        ->printSource('')
        ->printer(33)
        ->content('foo')
        ->send();
});

test('content type is required', function () {
    $this->expectException(PrintTaskFailed::class);
    $this->expectExceptionMessage('Content type must be specified for this driver!');

    $this->printNode
        ->newPrintTask()
        ->printer(33)
        ->send();
});
