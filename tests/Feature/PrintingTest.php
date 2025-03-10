<?php

declare(strict_types=1);

use Rawilk\Printing\Drivers\PrintNode\PrintTask as PrintNodePrintTask;
use Rawilk\Printing\Enums\PrintDriver;
use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Factory;
use Rawilk\Printing\Tests\Feature\Drivers\CustomDriver\Driver\PrintTask as CustomDriverPrintTask;
use Rawilk\Printing\Tests\Fixtures\Drivers\CustomDriver;

beforeEach(function () {
    config([
        'printing.driver' => PrintDriver::PrintNode->value,
        'printing.drivers.custom' => [
            'driver' => 'custom',
            'api_key' => '123456',
        ],
    ]);

    app()[Factory::class]->extend('custom', fn (array $config) => new CustomDriver($config['api_key']));
});

test('can choose drivers at runtime', function () {
    // Passing nothing into driver should give us the default driver
    expect(Printing::driver()->newPrintTask())->toBeInstanceOf(PrintNodePrintTask::class)
        ->and(Printing::driver(PrintDriver::PrintNode)->newPrintTask())->toBeInstanceOf(PrintNodePrintTask::class)
        ->and(Printing::driver('custom')->newPrintTask())->toBeInstanceOf(CustomDriverPrintTask::class);
});

test('the driver should use the default driver even after driver method has been called', function () {
    expect(Printing::newPrintTask())->toBeInstanceOf(PrintNodePrintTask::class)
        ->and(Printing::driver('custom')->newPrintTask())->toBeInstanceOf(CustomDriverPrintTask::class)
        // Should be the default (configured as PrintNode in our test)
        ->and(Printing::newPrintTask())->toBeInstanceOf(PrintNodePrintTask::class);
});
