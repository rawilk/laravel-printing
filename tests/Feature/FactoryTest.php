<?php

/** @deprecated Move to Unit test */

declare(strict_types=1);

use Rawilk\Printing\Drivers\Cups\Cups;
use Rawilk\Printing\Drivers\PrintNode\PrintNodeTemp;
use Rawilk\Printing\Exceptions\DriverConfigNotFound;
use Rawilk\Printing\Exceptions\InvalidDriverConfig;
use Rawilk\Printing\Exceptions\UnsupportedDriver;
use Rawilk\Printing\Factory;
use Rawilk\Printing\Tests\Fixtures\Drivers\CustomDriver;

it('creates the printnode driver', function () {
    config([
        'printing.driver' => 'printnode',
    ]);

    $factory = new Factory(config('printing'));

    expect($factory->driver())->toBeInstanceOf(PrintNodeTemp::class);
});

test('printnode driver throws an exception if missing api key', function () {
    config([
        'printing.driver' => 'printnode',
        'printing.drivers.printnode.key' => null,
    ]);

    $factory = new Factory(config('printing'));

    $this->expectException(InvalidDriverConfig::class);

    $factory->driver();
});

it('throws an exception for missing driver configs', function () {
    config([
        'printing.driver' => 'printnode',
        'printing.drivers.printnode' => null,
    ]);

    $factory = new Factory(config('printing'));

    $this->expectException(DriverConfigNotFound::class);

    $factory->driver();
});

it('throws an exception for unsupported drivers with missing configs', function () {
    config([
        'printing.driver' => 'foo',
    ]);

    $factory = new Factory(config('printing'));

    $this->expectException(DriverConfigNotFound::class);

    $factory->driver();
});

it('creates the cups driver with no remote server config', function () {
    config([
        'printing.driver' => 'cups',
        'printing.drivers.cups' => [],
    ]);

    $factory = new Factory(config('printing'));

    expect($factory->driver())->toBeInstanceOf(Cups::class);
});

it('creates a cups driver with remote server', function () {
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

    expect($factory->driver())->toBeInstanceOf(Cups::class);
});

it('throws an exception if missing the username or password for a remote cups server', function () {
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
});

test('can be extended', function () {
    config([
        'printing.drivers.custom' => [
            'driver' => 'custom_driver',
            'api_key' => '123456',
        ],
        'printing.driver' => 'custom',
    ]);

    app()['printing.factory']->extend('custom_driver', fn (array $config) => new CustomDriver($config['api_key']));

    expect(app()['printing.factory']->driver())->toBeInstanceOf(CustomDriver::class);
    expect(app()['printing.factory']->driver()->apiKey)->toEqual('123456');
});

it('throws an exception for unsupported drivers', function () {
    config([
        'printing.drivers.custom' => [],
        'printing.driver' => 'custom',
    ]);

    // An exception should be thrown for custom drivers if the "extend" method is not called
    // for the driver on the printing factory.
    $this->expectException(UnsupportedDriver::class);

    app()['printing.factory']->driver();
});
