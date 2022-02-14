<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature;

use Rawilk\Printing\Drivers\PrintNode\PrintTask as PrintnodePrintTask;
use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Tests\Feature\Drivers\CustomDriver\Driver\CustomDriver;
use Rawilk\Printing\Tests\Feature\Drivers\CustomDriver\Driver\PrintTask as CustomDriverPrintTask;
use Rawilk\Printing\Tests\TestCase;

class PrintingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'printing.driver' => 'printnode',
            'printing.drivers.custom' => [
                'driver' => 'custom',
                'api_key' => '123456',
            ],
        ]);

        $this->app['printing.factory']->extend('custom', fn (array $config) => new CustomDriver($config['api_key']));
    }

    /** @test */
    public function can_choose_drivers_at_runtime(): void
    {
        // Passing nothing into driver should give us the default driver
        $this->assertInstanceOf(PrintnodePrintTask::class, Printing::driver()->newPrintTask());

        $this->assertInstanceOf(PrintnodePrintTask::class, Printing::driver('printnode')->newPrintTask());
        $this->assertInstanceOf(CustomDriverPrintTask::class, Printing::driver('custom')->newPrintTask());
    }

    /** @test */
    public function the_driver_should_use_the_default_driver_even_after_driver_method_has_been_called(): void
    {
        $this->assertInstanceOf(PrintnodePrintTask::class, Printing::newPrintTask());
        $this->assertInstanceOf(CustomDriverPrintTask::class, Printing::driver('custom')->newPrintTask());

        // should use the default (configured as printnode in our test)
        $this->assertInstanceOf(PrintnodePrintTask::class, Printing::newPrintTask());
    }
}
