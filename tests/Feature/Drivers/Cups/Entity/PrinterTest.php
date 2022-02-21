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
    expect($toArray)->toEqual($expected);
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

    expect($json)->toEqual($expected);
});

test('can get the id of the printer', function () {
    expect(createPrinter()->id())->toEqual('localhost:631');
});

test('can get the status of the printer', function () {
    $printer = createPrinter();

    expect($printer->isOnline())->toBeTrue();
    expect($printer->status())->toEqual('online');

    $printer->cupsPrinter()->setStatus('offline');

    expect($printer->isOnline())->toBeFalse();
});

test('can get printer description', function () {
    $printer = createPrinter();

    $printer->cupsPrinter()->setAttribute('printer-info', 'Some description');

    expect($printer->description())->toEqual('Some description');
});

test('can get the printers trays', function () {
    $printer = createPrinter();

    expect($printer->trays())->toHaveCount(0);

    // Capabilities is cached after first retrieval, so we'll just use a fresh instance to test this
    $printer = createPrinter();

    $printer->cupsPrinter()->setAttribute('media-source-supported', ['Tray 1']);

    expect($printer->trays())->toHaveCount(1);
    expect($printer->trays()[0])->toEqual('Tray 1');
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
