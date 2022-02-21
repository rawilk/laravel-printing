<?php

declare(strict_types=1);

use Rawilk\Printing\Drivers\PrintNode\PrintTask as PrintnodePrintTask;
use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Tests\Feature\Drivers\CustomDriver\Driver\CustomDriver;
use Rawilk\Printing\Tests\Feature\Drivers\CustomDriver\Driver\PrintTask as CustomDriverPrintTask;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    config([
        'printing.driver' => 'printnode',
        'printing.drivers.custom' => [
            'driver' => 'custom',
            'api_key' => '123456',
        ],
    ]);

    app()['printing.factory']->extend('custom', fn (array $config) => new CustomDriver($config['api_key']));
});

test('can choose drivers at runtime', function () {
    // Passing nothing into driver should give us the default driver
    $this->assertInstanceOf(PrintnodePrintTask::class, Printing::driver()->newPrintTask());

    $this->assertInstanceOf(PrintnodePrintTask::class, Printing::driver('printnode')->newPrintTask());
    $this->assertInstanceOf(CustomDriverPrintTask::class, Printing::driver('custom')->newPrintTask());
});

test('the driver should use the default driver even after driver method has been called', function () {
    $this->assertInstanceOf(PrintnodePrintTask::class, Printing::newPrintTask());
    $this->assertInstanceOf(CustomDriverPrintTask::class, Printing::driver('custom')->newPrintTask());

    // should use the default (configured as printnode in our test)
    $this->assertInstanceOf(PrintnodePrintTask::class, Printing::newPrintTask());
});
