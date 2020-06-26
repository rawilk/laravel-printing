<?php

namespace Rawilk\Printing\Tests\Feature;

use Rawilk\Printing\Drivers\PrintNode\PrintNode;
use Rawilk\Printing\Exceptions\DriverConfigNotFound;
use Rawilk\Printing\Exceptions\InvalidDriverConfig;
use Rawilk\Printing\Factory;
use Rawilk\Printing\Tests\TestCase;

class FactoryTest extends TestCase
{
    /** @test */
    public function it_creates_the_printnode_driver(): void
    {
        config([
            'printing.driver' => 'printnode',
        ]);

        $factory = new Factory(config('printing'));

        $this->assertInstanceOf(PrintNode::class, $factory->driver());
    }

    /** @test */
    public function printnode_driver_throws_an_exception_if_missing_api_key(): void
    {
        config([
            'printing.driver' => 'printnode',
            'printing.drivers.printnode.key' => null,
        ]);

        $factory = new Factory(config('printing'));

        $this->expectException(InvalidDriverConfig::class);

        $factory->driver();
    }

    /** @test */
    public function it_throws_an_exception_for_missing_driver_configs(): void
    {
        config([
            'printing.driver' => 'printnode',
            'printing.drivers.printnode' => [],
        ]);

        $factory = new Factory(config('printing'));

        $this->expectException(DriverConfigNotFound::class);

        $factory->driver();
    }

    /** @test */
    public function it_throws_an_exception_for_unsupported_drivers(): void
    {
        config([
            'printing.driver' => 'foo',
        ]);

        $factory = new Factory(config('printing'));

        $this->expectException(DriverConfigNotFound::class);

        $factory->driver();
    }
}
