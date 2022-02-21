<?php

declare(strict_types=1);

use Carbon\Carbon;
use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Entity\PrinterCapabilities;

test('can be create from an array', function () {
    $printer = new Printer(samplePrintNodeData('printer_single')[0]);

    expect($printer->id)->toBe(39);
    expect($printer->computer)->toBeInstanceOf(Computer::class);
    expect($printer->computer->id)->toBe(13);
    expect($printer->name)->toEqual('Microsoft XPS Document Writer');
    expect($printer->description)->toEqual('Microsoft XPS Document Writer');
    expect($printer->capabilities)->toBeInstanceOf(PrinterCapabilities::class);
    expect($printer->capabilities->bins)->toEqual(['Automatically Select']);
    expect($printer->capabilities->bins)->toEqual($printer->trays());
    expect($printer->isOnline())->toBeTrue();
    expect($printer->created)->toBeInstanceOf(Carbon::class);
    expect($printer->created->format('Y-m-d H:i:s'))->toEqual('2015-11-17 13:02:37');
});

test('casts to array', function () {
    $data = samplePrintNodeData('printer_single')[0];
    $printer = new Printer($data);

    $asArray = $printer->toArray();

    foreach ($data as $key => $value) {
        $this->assertArrayHasKey($key, $asArray);
    }

    expect($asArray['computer'])->toBeArray();
    expect($asArray['capabilities'])->toBeArray();
    $this->assertArrayHasKey('createTimestamp', $asArray['computer']);
});
