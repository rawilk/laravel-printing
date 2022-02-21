<?php

declare(strict_types=1);

use Carbon\Carbon;
use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Entity\PrinterCapabilities;
use Rawilk\Printing\Api\PrintNode\Requests\PrinterRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

uses(PrintNodeTestCase::class);

test('can find an accounts printer', function () {
    $this->fakeRequest('printers/39', 'printer_single');

    $printer = (new PrinterRequest('1234'))->response(39);

    $this->assertNotNull($printer);
    expect($printer->id)->toBe(39);
    expect($printer->computer)->toBeInstanceOf(Computer::class);
    expect($printer->name)->toEqual('Microsoft XPS Document Writer');
    expect($printer->description)->toEqual('Microsoft XPS Document Writer');
    expect($printer->capabilities)->toBeInstanceOf(PrinterCapabilities::class);
    expect($printer->trays())->toEqual(['Automatically Select']);
    expect($printer->created)->toBeInstanceOf(Carbon::class);
    expect($printer->created->format('Y-m-d H:i:s'))->toEqual('2015-11-17 13:02:37');
    expect($printer->state)->toEqual('online');
    expect($printer->isOnline())->toBeTrue();
    expect($printer->isCollate())->toBeFalse();
    expect($printer->isColor())->toBeTrue();
    expect($printer->copies())->toBe(1);
});

test('printer knows if printnode says it is offline', function () {
    $this->fakeRequest('printers/40', 'printer_single_offline');

    $printer = (new PrinterRequest('1234'))->response(40);

    expect($printer->isOnline())->toBeFalse();
});

test('printer capabilities will always be available', function () {
    $this->fakeRequest('printers/34', 'printer_single_no_capabilities');

    $printer = (new PrinterRequest('1234'))->response(34);

    expect($printer->capabilities)->toBeInstanceOf(PrinterCapabilities::class);
    expect($printer->trays())->toBeArray();
    expect($printer->trays())->toBeEmpty();
});

test('returns null for no printer found', function () {
    $this->fakeRequest('printers/1234', 'printer_single_not_found');

    $printer = (new PrinterRequest('1234'))->response(1234);

    expect($printer)->toBeNull();
});
