<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Contracts\PrintJob;
use Rawilk\Printing\Contracts\PrintTask;
use Rawilk\Printing\Drivers\PrintNode\PrintTask as PrintNodePrintTask;
use Rawilk\Printing\Enums\PrintDriver;
use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Factory;
use Rawilk\Printing\Printing as BaseDriver;
use Rawilk\Printing\Tests\Fixtures\Drivers\Custom\CustomDriver;
use Rawilk\Printing\Tests\Fixtures\Drivers\Custom\PrintTask as CustomDriverPrintTask;

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

it('forwards extra parameters to the driver implementations', function () {
    $receivedParams = [];

    $mockDriver = new class($receivedParams) implements Driver
    {
        public function __construct(protected array &$receivedParams)
        {
        }

        public function newPrintTask(): PrintTask
        {
            return Mockery::mock(PrintTask::class);
        }

        public function printer($printerId = null): ?Printer
        {
            $this->receivedParams[] = func_get_args();

            return Mockery::mock(Printer::class);
        }

        public function printers(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
        {
            $this->receivedParams[] = func_get_args();

            return collect();
        }

        public function printJobs(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
        {
            $this->receivedParams[] = func_get_args();

            return collect();
        }

        public function printJob($jobId = null): ?PrintJob
        {
            $this->receivedParams[] = func_get_args();

            return Mockery::mock(PrintJob::class);
        }

        public function printerPrintJobs($printerId, ?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
        {
            $this->receivedParams[] = func_get_args();

            return collect();
        }

        public function printerPrintJob($printerId, $jobId): ?PrintJob
        {
            $this->receivedParams[] = func_get_args();

            return Mockery::mock(PrintJob::class);
        }
    };

    $printing = new BaseDriver($mockDriver);

    // Call methods with extra parameters
    $printing->printer(123, ['extra' => 'value'], 'option1');
    $printing->printJob(456, ['status' => 'pending']);
    $printing->printers(10, 20, 'asc', 'additional', 'params');
    $printing->printJobs(5, 10, 'desc', 'other', 'values');
    $printing->printerPrintJobs(789, 15, 30, 'desc', 'extra1', 'extra2');
    $printing->printerPrintJob(321, 654, ['meta' => 'data']);

    // Assert that parameters were forwarded correctly
    expect($receivedParams)->toEqualCanonicalizing([
        [123, ['extra' => 'value'], 'option1'],
        [456, ['status' => 'pending']],
        [10, 20, 'asc', 'additional', 'params'],
        [5, 10, 'desc', 'other', 'values'],
        [789, 15, 30, 'desc', 'extra1', 'extra2'],
        [321, 654, ['meta' => 'data']],
    ]);
});

test('a driver implementation can define extra parameters on the interface methods', function () {
    $data = [];

    // Here we are adding an extra $params parameter to the `printers()` method
    // required by the interface.
    $mockDriver = new class($data) implements Driver
    {
        public function __construct(protected array &$data)
        {
        }

        public function newPrintTask(): PrintTask
        {
            return Mockery::mock(PrintTask::class);
        }

        public function printer($printerId = null): ?Printer
        {
            return Mockery::mock(Printer::class);
        }

        public function printers(?int $limit = null, ?int $offset = null, ?string $dir = null, array $params = []): Collection
        {
            $this->data = $params;

            return collect();
        }

        public function printJobs(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
        {
            return collect();
        }

        public function printJob($jobId = null): ?PrintJob
        {
            return Mockery::mock(PrintJob::class);
        }

        public function printerPrintJobs($printerId, ?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
        {
            return collect();
        }

        public function printerPrintJob($printerId, $jobId): ?PrintJob
        {
            return Mockery::mock(PrintJob::class);
        }
    };

    $printing = new BaseDriver($mockDriver);

    $printing->printers(null, null, null, ['foo' => 'bar']);

    expect($data)->toEqualCanonicalizing(['foo' => 'bar']);
});

test('printnode api key can be updated from the facade', function () {
    Printing::driver(PrintDriver::PrintNode)->getDriver()->setApiKey('new-key');

    $driver = app(Factory::class)->driver(PrintDriver::PrintNode);

    expect($driver->getApiKey())->toBe('new-key');
});
