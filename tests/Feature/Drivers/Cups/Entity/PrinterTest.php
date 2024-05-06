<?php

declare(strict_types=1);

test('can be cast to array', function () {
    $printer = createCupsPrinter();

    $toArray = $printer->toArray();

    $expected = [
        'id' => 'localhost:631',
        'name' => 'printer-name',
        'description' => null,
        'online' => true,
        'status' => 'idle',
        'trays' => [],
    ];

    $this->assertNotEmpty($toArray);
    expect($toArray)->toMatchArray($expected);
});

test('can be cast to json', function () {
    $printer = createCupsPrinter();

    $json = json_decode(json_encode($printer), true);
    $json['capabilities'] = [];
    $json = json_encode($json);

    $expected = json_encode([
        'id' => 'localhost:631',
        'name' => 'printer-name',
        'description' => null,
        'online' => true,
        'status' => 'idle',
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
    expect($printer->status())->toEqual('idle');
});

test('can get printer description', function () {
    $printer = createCupsPrinter(['printer-info' => new \Rawilk\Printing\Api\Cups\Types\TextWithoutLanguage('Some description')]);
    expect($printer->description())->toEqual('Some description');
});

test('can get the printers trays', function () {
    $printer = createCupsPrinter();

    expect($printer->trays())->toHaveCount(0);

    $printer = createCupsPrinter(['media-source-supported' => new \Rawilk\Printing\Api\Cups\Types\Primitive\Keyword(['Tray 1'])]);

    expect($printer->trays())->toHaveCount(1);
    expect($printer->trays()[0])->toEqual('Tray 1');
});
