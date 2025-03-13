<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Rawilk\Printing\Api\PrintNode\PrintNode;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Driver as DriverContract;
use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Contracts\PrintJob;
use Rawilk\Printing\Contracts\PrintTask;
use Rawilk\Printing\Drivers\Cups\Cups as CupsDriver;
use Rawilk\Printing\Drivers\PrintNode\PrintNode as PrintNodeDriver;
use Rawilk\Printing\Enums\PrintDriver;
use Rawilk\Printing\Exceptions\DriverConfigNotFound;
use Rawilk\Printing\Exceptions\InvalidDriverConfig;
use Rawilk\Printing\Exceptions\UnsupportedDriver;
use Rawilk\Printing\Factory;
use Rawilk\Printing\Tests\Fixtures\Drivers\Custom\CustomDriver;

it('can update configuration', function () {
    $factory = new Factory([
        'driver' => 'printnode',
        'drivers' => [
            'printnode' => ['key' => '1234'],
            'foo' => ['key' => 'bar'],
        ],
    ]);

    $factory->updateConfig([
        'drivers' => [
            'printnode' => [
                'foo' => 'bar',
            ],
            'cups' => [
                'ip' => '127.0.0.1',
            ],
            'foo' => ['key' => 'baz'],
        ],
    ]);

    expect($factory->getConfig())->toEqualCanonicalizing([
        'driver' => 'printnode',
        'drivers' => [
            'printnode' => [
                'key' => '1234',
                'foo' => 'bar',
            ],
            'foo' => ['key' => 'baz'],
            'cups' => ['ip' => '127.0.0.1'],
        ],
    ]);
});

it('can create a driver by name', function (PrintDriver $driver, array $config, Closure $expect) {
    $factory = new Factory([
        'drivers' => [
            $driver->value => $config,
        ],
    ]);

    $driver = $factory->driver($driver);

    expect($driver)->toBeInstanceOf(DriverContract::class);

    $expect($driver);
})->with([
    'printnode' => fn () => [
        'driver' => PrintDriver::PrintNode,
        'config' => ['key' => 'foo'],
        'expect' => function (PrintNodeDriver $driver) {
            expect($driver->getApiKey())->toBe('foo');
        },
    ],
    'cups' => fn () => [
        'driver' => PrintDriver::Cups,
        'config' => ['ip' => '127.0.0.1', 'port' => 8080],
        'expect' => function (CupsDriver $driver) {
            expect($driver->getConfig())->toEqualCanonicalizing([
                'ip' => '127.0.0.1',
                'username' => null,
                'password' => null,
                'port' => 8080,
                'secure' => false,
            ]);
        },
    ],
]);

it('throws an exception for missing driver configs', function () {
    $factory = new Factory([
        'driver' => PrintDriver::PrintNode->value,
        'drivers' => [
            PrintDriver::PrintNode->value => null,
        ],
    ]);

    $factory->driver(PrintDriver::PrintNode);
})->throws(DriverConfigNotFound::class);

it('throws an exception for unsupported drivers', function () {
    $factory = new Factory([]);

    $factory->driver('unsupported');
})->throws(UnsupportedDriver::class);

it('supports custom drivers', function () {
    config([
        'printing.drivers.custom' => [
            'driver' => 'custom_driver',
            'api_key' => 'my-key',
        ],
    ]);

    $factory = new Factory(config('printing'));

    $factory->extend('custom_driver', fn (array $config) => new CustomDriver($config['api_key']));

    $driver = $factory->driver('custom');

    expect($driver)
        ->toBeInstanceOf(CustomDriver::class)
        ->apiKey->toBe('my-key');
});

test('custom drivers do not require a config', function () {
    $factory = new Factory([]);

    $driverClass = new class implements Driver
    {
        public string $foo = 'bar';

        public function newPrintTask(): PrintTask
        {
        }

        public function printer($printerId = null): ?Printer
        {
        }

        public function printers(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
        {
        }

        public function printJobs(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
        {
        }

        public function printJob($jobId = null): ?PrintJob
        {
        }

        public function printerPrintJobs($printerId, ?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
        {
        }

        public function printerPrintJob($printerId, $jobId): ?PrintJob
        {
        }
    };

    $factory->extend('custom_driver', fn () => new $driverClass);

    $driver = $factory->driver('custom_driver');

    expect($driver)
        ->toBeInstanceOf($driverClass::class)
        ->foo->toBe('bar');
});

describe('printnode', function () {
    beforeEach(function () {
        PrintNode::setApiKey(null);

        config([
            'printing.driver' => PrintDriver::PrintNode->value,

            'printing.drivers' => [
                PrintDriver::PrintNode->value => [
                    'key' => '1234',
                ],
            ],
        ]);
    });

    it('creates the printnode driver', function () {
        $factory = new Factory(config('printing'));

        $driver = $factory->driver();

        expect($driver)->toBeInstanceOf(PrintNodeDriver::class)
            ->and($driver->getApiKey())->toBe('1234');
    });

    test('printnode api key can be null in the config', function () {
        config()->set('printing.drivers.' . PrintDriver::PrintNode->value . '.key', null);

        $factory = new Factory(config('printing'));

        $driver = $factory->driver();

        expect($driver->getApiKey())->toBeNull();
    });

    test('printnode driver throws exception if missing api key', function () {
        config()->set('printing.drivers.' . PrintDriver::PrintNode->value . '.key', '');

        $factory = new Factory(config('printing'));

        $factory->driver();
    })->throws(InvalidDriverConfig::class, 'You must provide an api key for the PrintNode driver.');
});

describe('cups', function () {
    beforeEach(function () {
        config([
            'printing.driver' => PrintDriver::Cups->value,

            'printing.drivers' => [
                PrintDriver::Cups->value => [
                    'ip' => '127.0.0.1',
                ],
            ],
        ]);
    });

    it('creates the cups driver', function () {
        $factory = new Factory(config('printing'));

        $driver = $factory->driver();

        expect($driver)->toBeInstanceOf(CupsDriver::class)
            ->getConfig()->toHaveKey('ip', '127.0.0.1');
    });

    test('ip can be null in config', function () {
        config()->set('printing.drivers.' . PrintDriver::Cups->value . '.ip', null);

        $factory = new Factory(config('printing'));

        $driver = $factory->driver();

        expect($driver->getConfig()['ip'])->toBeNull();
    });

    it('handles invalid config', function (array $config, string $exceptionMessage) {
        config()->set('printing.drivers.' . PrintDriver::Cups->value, $config);

        $factory = new Factory(config('printing'));

        $this->expectException(InvalidDriverConfig::class);
        $this->expectExceptionMessage($exceptionMessage);

        $factory->driver();
    })->with([
        'blank ip' => [['ip' => ''], 'An IP address is required'],
        'invalid secure' => [['secure' => 'true'], 'A boolean value must be provided for the secure option'],
        'blank port' => [['port' => ''], 'A port must be provided'],
        'non-numeric port' => [['port' => 'foo'], 'A valid port number'],
        'invalid port' => [['port' => 0], 'A valid port number'],
    ]);
});
