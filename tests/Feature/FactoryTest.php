<?php

namespace Rawilk\Printing\Tests\Feature;

use Rawilk\Printing\Drivers\Cups\Cups;
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

        self::assertInstanceOf(PrintNode::class, $factory->driver());
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
            'printing.drivers.printnode' => null,
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

    /** @test */
    public function it_creates_the_cups_driver_with_no_remote_server_config(): void
    {
        config([
            'printing.driver' => 'cups',
            'printing.drivers.cups' => [],
        ]);

        $factory = new Factory(config('printing'));

        self::assertInstanceOf(Cups::class, $factory->driver());
    }

    /** @test */
    public function it_creates_a_cups_driver_with_remote_server(): void
    {
        config([
            'printing.driver' => 'cups',
            'printing.drivers.cups' => [
                'ip' => '127.0.0.1',
                'username' => 'foo',
                'password' => 'bar',
                'port' => 631,
            ],
        ]);

        $factory = new Factory(config('printing'));

        self::assertInstanceOf(Cups::class, $factory->driver());
    }

    /** @test */
    public function it_throws_an_exception_if_missing_the_username_or_password_for_a_remote_cups_server(): void
    {
        config([
            'printing.driver' => 'cups',
            'printing.drivers.cups' => [
                'ip' => '127.0.0.1',
                'username' => '',
                'password' => 'bar',
                'port' => 631,
            ],
        ]);

        $factory = new Factory(config('printing'));

        $this->expectException(InvalidDriverConfig::class);

        $factory->driver();
    }
}
