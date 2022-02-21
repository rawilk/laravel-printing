<?php

declare(strict_types=1);

use Rawilk\Printing\Drivers\Cups\Support\Client;
use Smalot\Cups\Builder\Builder;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Transport\ResponseParser;

beforeEach(function () {
    $client = new Client;
    $responseParser = new ResponseParser;
    $builder = new Builder;

    $this->jobManager = new JobManager($builder, $client, $responseParser);
});

test('can be cast to array', function () {
    $printer = createCupsPrinter();

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
    $printer = createCupsPrinter();

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
    expect(createCupsPrinter()->id())->toEqual('localhost:631');
});

test('can get the status of the printer', function () {
    $printer = createCupsPrinter();

    expect($printer->isOnline())->toBeTrue();
    expect($printer->status())->toEqual('online');

    $printer->cupsPrinter()->setStatus('offline');

    expect($printer->isOnline())->toBeFalse();
});

test('can get printer description', function () {
    $printer = createCupsPrinter();

    $printer->cupsPrinter()->setAttribute('printer-info', 'Some description');

    expect($printer->description())->toEqual('Some description');
});

test('can get the printers trays', function () {
    $printer = createCupsPrinter();

    expect($printer->trays())->toHaveCount(0);

    // Capabilities are cached after first retrieval, so we'll just use a fresh instance to test this
    $printer = createCupsPrinter();

    $printer->cupsPrinter()->setAttribute('media-source-supported', ['Tray 1']);

    expect($printer->trays())->toHaveCount(1);
    expect($printer->trays()[0])->toEqual('Tray 1');
});
