<?php

declare(strict_types=1);

use Rawilk\Printing\Drivers\Cups\Entity\Printer;
use Rawilk\Printing\Drivers\Cups\Support\Client;
use Rawilk\Printing\Tests\TestCase;
use Smalot\Cups\Builder\Builder;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Model\Printer as CupsPrinter;
use Smalot\Cups\Transport\ResponseParser;

uses(TestCase::class);

beforeEach(function () {
    $client = new Client;
    $responseParser = new ResponseParser;
    $builder = new Builder;

    $this->jobManager = new JobManager($builder, $client, $responseParser);
});

test('can be cast to array', function () {
    $printer = createPrinter();

    $toArray = $printer->toArray();

    $expected = [
        'id' => 'localhost:631',
        'name' => 'printer-name',
        'description' => null,
        'online' => true,
        'status' => 'online',
        'trays' => [],
        'capabilities' => [],
    ];

    $this->assertNotEmpty($toArray);
    $this->assertEquals($expected, $toArray);
});

test('can be cast to json', function () {
    $printer = createPrinter();

    $json = json_encode($printer);

    $expected = json_encode([
        'id' => 'localhost:631',
        'name' => 'printer-name',
        'description' => null,
        'online' => true,
        'status' => 'online',
        'trays' => [],
        'capabilities' => [],
    ]);

    $this->assertEquals($expected, $json);
});

test('can get the id of the printer', function () {
    $this->assertEquals('localhost:631', createPrinter()->id());
});

test('can get the status of the printer', function () {
    $printer = createPrinter();

    $this->assertTrue($printer->isOnline());
    $this->assertEquals('online', $printer->status());

    $printer->cupsPrinter()->setStatus('offline');

    $this->assertFalse($printer->isOnline());
});

test('can get printer description', function () {
    $printer = createPrinter();

    $printer->cupsPrinter()->setAttribute('printer-info', 'Some description');

    $this->assertEquals('Some description', $printer->description());
});

test('can get the printers trays', function () {
    $printer = createPrinter();

    $this->assertCount(0, $printer->trays());

    // Capabilities is cached after first retrieval, so we'll just use a fresh instance to test this
    $printer = createPrinter();

    $printer->cupsPrinter()->setAttribute('media-source-supported', ['Tray 1']);

    $this->assertCount(1, $printer->trays());
    $this->assertEquals('Tray 1', $printer->trays()[0]);
});

// Helpers
function createPrinter(): Printer
{
    $cupsPrinter = new CupsPrinter;
    $cupsPrinter->setName('printer-name')
        ->setUri('localhost:631')
        ->setStatus('online');

    return new Printer($cupsPrinter, test()->jobManager);
}
