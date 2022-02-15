---
title: Upgrade Guide
sort: 3
---

## Upgrade from v2 to v3

### \Rawilk\Printing\Contracts\Driver
Any custom driver implementing this interface must make the following changes:
- Rename `find()` method to `printer()`
- Add a method for retrieving a list of print jobs with the following signature: `public function printJobs(int|null $limit = null, int|null $offset = null, string|null $dir = null): Collection`
- Add a method for retrieving a print job with the following signature: `public function printJob($jobId = null): null|\Rawilk\Printing\Contracts\PrintJob`
- Add a method for retrieving a printer's print jobs with the following signature: `public function printerPrintJobs($printerId, int|null $limit = null, int|null $offset = null, string|null $dir = null): Collection`
- Add a method for retrieving a print job from a printer with the following signature: `public function printerPrintJob($printerId, $jobId): null|\Rawilk\Printing\Contracts\PrintJob`
- The `printers()` method signature has changed to include a few parameters: `public function printers(int|null $limit = null, int|null $offset = null, string|null $dir = null): Collection`

### \Rawilk\Printing\Contracts\PrintJob
Any custom driver implementing this interface must make the following changes:
- Add a `null|Carbon` return type to the `date()` method signature

### \Rawilk\Printing\Facades\Printing
If you were using the `Printing::find(...)` method to find a specific printer, you should change any references of it to: `Printing::printer(...)`.

### PrintNode Dependencies
In previous versions, we relied on `printnode/printnode-php` for making the api calls to PrintNode. In v3, we've removed it entirely in favor of writing our own
API wrapper to interact with their API. The biggest reason for doing this is because their PHP package has not been maintained for several years now and it's become
problematic for using it in our own projects. With our own API wrapper, we can maintain it as we see fit and as needed to keep it compatible with both their API and
any newer versions of PHP/Laravel.

If you're using PrintNode as your printing driver, you should remove the `printnode/printnode-php` package as a composer dependency as it's no longer needed:

```bash
composer remove printnode/printnode-php
```

## Upgrade from v1 to v2

### Your Environment

You will need to ensure your environment supports php v8, and your laravel installation must be running on at least version 8.0.

### Driver Dependencies

In v2, `laravel-printing` no longer automatically requires the third-party dependencies required for each driver. Unless you are using
a custom driver, you will need to pull in one of the following dependencies depending on which driver you are using:

-   **PrintNode:** `composer require printnode/printnode-php`
-   **CUPS:** `composer require smalot/cups-ipp`

### PrintTask Contract

If you have any custom drivers created and are implementing the `Rawilk\Printing\Contracts\PrintTask` interface, you will need to update
the following method signatures:

-   `public function printer(Printer|string|null|int $printerId): self;`
