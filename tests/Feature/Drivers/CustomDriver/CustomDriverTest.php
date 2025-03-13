<?php

declare(strict_types=1);

use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Factory;
use Rawilk\Printing\Tests\Fixtures\Drivers\Custom\CustomDriver;

beforeEach(function () {
    config([
        'printing.driver' => 'custom',
        'printing.drivers.custom' => [
            'driver' => 'custom_driver',
            'api_key' => '123456',
        ],
    ]);

    app()[Factory::class]->extend('custom_driver', fn (array $config) => new CustomDriver($config['api_key']));
});

it('can list a custom drivers printers', function () {
    $printers = Printing::printers();

    expect($printers)->toHaveCount(2)
        ->and($printers[0])->id()->toBe('printer_one')
        ->and($printers[1])->id()->toBe('printer_two');
});

it('can find a custom drivers printer', function () {
    $printer = Printing::printer('printer_one');

    expect($printer)
        ->id()->toBe('printer_one')
        ->isOnline()->toBeTrue();
});

test('can get a custom drivers default printer', function () {
    config(['printing.default_printer_id' => 'printer_two']);

    expect(Printing::defaultPrinterId())->toBe('printer_two');

    $defaultPrinter = Printing::defaultPrinter();

    expect($defaultPrinter)
        ->id()->toBe('printer_two')
        ->isOnline()->toBeFalse();
});

test('can create new print tasks for a custom driver', function () {
    $job = Printing::newPrintTask()
        ->printer('printer_one')
        ->content('hello world')
        ->send();

    expect($job)
        ->state()->toBe('success')
        ->printerId()->toBe('printer_one');
});
