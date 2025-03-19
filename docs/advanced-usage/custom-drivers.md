---
title: Custom Drivers
sort: 4
---

**Since: 1.3.0**

## Introduction

If you need to use a driver that isn't supported by the package, you can easily add your own custom driver. Adding a custom driver will require you to add the driver's config to the `drivers` in the config file, and to extend the printing factory in a service provider.

A custom driver could also be used to either extend or completely replace a built-in driver from the package if your needs differ than what the package offers.

## Configuring a Custom Driver

Add your custom driver configuration under `drivers` in `config/printing`. The minimum required for your driver config
is a `driver` key.

```php
'driver' => 'my_custom_driver',

'drivers' => [
    ...
    'my_custom_driver' => [
        'driver' => 'custom', // This value is required
        // any other configuration needed
    ],
],
```

You can change `custom` and `my_custom_driver` to whatever you want. Any data you specify in the configuration of your custom driver will be passed to the closure you provide to the printing factory when extending it.

## Defining a Custom Driver

Once you have your custom driver configuration defined, you need to tell the printing package how to create it. This is done by extending the print factory used by this package. In a service provider, you can do it like this:

```php
use Rawilk\Printing\Factory;

public function register(): void
{
    $this->app[Factory::class]->extend('custom', function (array $config) {
        return new MyCustomDriver($config);
    });
}
```

The value you pass in as the first parameter needs to match what you defined as the **driver** key in your custom driver's configuration earlier.

In addition to the custom driver class, you will also need to implement the following interfaces, each of which are shown below:

- `Rawilk\Printing\Contracts\PrintTask`
- `Rawilk\Printing\Contracts\Printer`
- `Rawilk\Printing\Contracts\PrintJob`

### Driver Class

Your custom driver will need to implement the `Driver` interface.

```php
use Illuminate\Support\Collection;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Contracts\PrintJob;

class MyCustomDriver implements Driver
{
    public function __construct(protected array $config = [])
    {
    }

    public function newPrintTask(): PrintTask
    {
        return new PrintTask;
    }

    public function printer(
        $printerId = null,
    ): ?Printer {
        // ...
    }

    public function printers(
        ?int $limit = null,
        ?int $offset = null,
        ?string $dir = null,
    ): Collection {
        // ...
    }

    public function printJobs(
        ?int $limit = null,
        ?int $offset = null,
        ?string $dir = null,
    ): Collection {
        // ...
    }

    public function printJob(
        $jobId = null,
    ): ?PrintJob {
        // ...
    }

    /**
     * Return all jobs from a given printer.
     */
    public function printerPrintJobs(
        $printerId,
        ?int $limit = null,
        ?int $offset = null,
        ?string $dir = null,
    ): Collection {
        // ...
    }

    /**
     * Search for a print job from a given printer.
     */
    public function printerPrintJob(
        $printerId,
        $jobId,
    ): ?PrintJob {
        // ...
    }
}
```

> {tip} Like the built-in drivers, your custom driver may accept extra arguments for each of the `Driver` interface methods.

### Printer

Each driver needs an entity that implements the `Printer` interface, which represents a physical printer on your print server.

```php
use Illuminate\Support\Collection;
use Rawilk\Printing\Contracts\Printer as PrinterContract;

class Printer implements PrinterContract
{
    public function capabilities(): array {}

    public function description(): ?string {}

    public function id() {}

    public function isOnline() : bool {}

    public function name(): ?string {}

    public function status(): string {}

    public function trays(): array {}

    /**
     * @return Collection<int, \Rawilk\Printing\Contracts\PrintJob>
     */
    public function jobs(): Collection {}

    public function toArray(): array {}
}
```

### PrintJob

Each driver needs an entity that implements the `PrintJob` interface, which represents a job that has been sent to a printer on your print server.

```php
use Rawilk\Printing\Contracts\PrintJob as PrintJobContract;
use Carbon\CarbonInterface;

class PrintJob implements PrintJobContract
{
    public function date(): ?CarbonInterface {}

    public function id() {}

    public function name(): ?string {}

    public function printerId() {}

    public function printerName(): ?string {}

    public function state(): ?string {}

    public function toArray(): array {}
}
```

### PrintTask

The `PrintTask` implementation is what will be used to create and send new print jobs to a printer. The package provides a base PrintTask class that your driver may extend, or you are free to only implement the PrintTask interface instead.

```php
use Rawilk\Printing\PrintTask as BasePrintTask;

class PrintTask extends BasePrintTask
{
    public function send(): PrintJob {}
}
```
