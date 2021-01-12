---
title: Upgrade Guide
sort: 3
---

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
