<?php

declare(strict_types=1);

use Rawilk\Printing\Drivers\PrintNode\PrintTask as PrintnodePrintTask;
use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Tests\Feature\Drivers\CustomDriver\Driver\CustomDriver;
use Rawilk\Printing\Tests\Feature\Drivers\CustomDriver\Driver\PrintTask as CustomDriverPrintTask;

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
    expect(Printing::driver()->newPrintTask())->toBeInstanceOf(PrintnodePrintTask::class);

    expect(Printing::driver('printnode')->newPrintTask())->toBeInstanceOf(PrintnodePrintTask::class);
    expect(Printing::driver('custom')->newPrintTask())->toBeInstanceOf(CustomDriverPrintTask::class);
});

test('the driver should use the default driver even after driver method has been called', function () {
    expect(Printing::newPrintTask())->toBeInstanceOf(PrintnodePrintTask::class);
    expect(Printing::driver('custom')->newPrintTask())->toBeInstanceOf(CustomDriverPrintTask::class);

    // should use the default (configured as printnode in our test)
    expect(Printing::newPrintTask())->toBeInstanceOf(PrintnodePrintTask::class);
});
