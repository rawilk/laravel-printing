<?php

declare(strict_types=1);

use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Tests\Feature\Drivers\CustomDriver\Driver\CustomDriver;

beforeEach(function () {
    config([
        'printing.driver' => 'custom',
        'printing.drivers.custom' => [
            'driver' => 'custom_driver',
            'api_key' => '123456',
        ],
    ]);

    app()['printing.factory']->extend('custom_driver', fn (array $config) => new CustomDriver($config['api_key']));
});

test('can list a custom drivers printers', function () {
    expect(Printing::printers())->toHaveCount(2);
    expect(Printing::printers()[0]->id())->toEqual('printer_one');
    expect(Printing::printers()[1]->id())->toEqual('printer_two');
});

test('can find a custom drivers printer', function () {
    $printer = Printing::printer('printer_one');

    expect($printer->id())->toEqual('printer_one');
    expect($printer->isOnline())->toBeTrue();
});

test('can get a custom drivers default printer', function () {
    config(['printing.default_printer_id' => 'printer_two']);

    expect(Printing::defaultPrinterId())->toEqual('printer_two');

    $defaultPrinter = Printing::defaultPrinter();

    expect($defaultPrinter->id())->toEqual('printer_two');
    expect($defaultPrinter->isOnline())->toBeFalse();
});

test('can create new print tasks for a custom driver', function () {
    $job = Printing::newPrintTask()
        ->printer('printer_one')
        ->content('hello world')
        ->send();

    expect($job->state())->toEqual('success');
    expect($job->printerId())->toEqual('printer_one');
});
