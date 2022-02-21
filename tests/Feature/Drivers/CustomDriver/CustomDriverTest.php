<?php

declare(strict_types=1);

use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Tests\Feature\Drivers\CustomDriver\Driver\CustomDriver;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);

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
    $this->assertCount(2, Printing::printers());
    $this->assertEquals('printer_one', Printing::printers()[0]->id());
    $this->assertEquals('printer_two', Printing::printers()[1]->id());
});

test('can find a custom drivers printer', function () {
    $printer = Printing::printer('printer_one');

    $this->assertEquals('printer_one', $printer->id());
    $this->assertTrue($printer->isOnline());
});

test('can get a custom drivers default printer', function () {
    config(['printing.default_printer_id' => 'printer_two']);

    $this->assertEquals('printer_two', Printing::defaultPrinterId());

    $defaultPrinter = Printing::defaultPrinter();

    $this->assertEquals('printer_two', $defaultPrinter->id());
    $this->assertFalse($defaultPrinter->isOnline());
});

test('can create new print tasks for a custom driver', function () {
    $job = Printing::newPrintTask()
        ->printer('printer_one')
        ->content('hello world')
        ->send();

    $this->assertEquals('success', $job->state());
    $this->assertEquals('printer_one', $job->printerId());
});
