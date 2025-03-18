---
title: Introduction
sort: 1
---

## Introduction

Printing for Laravel allows your application to directly send PDF documents or raw text directly from a remote server to a printer on your local network.
Receipts can also be printed by first generating the raw text via the `Rawilk\Printing\Receipts\ReceiptPrinter` class, and then sending the text as a
raw print job via the `Printing` facade.

Here's a simple example of what you can do with this package:

```php
use Rawilk\Printing\Facades\Printing;

$printJob = Printing::newPrintTask()
    ->printer($printerId)
    ->file('path_to_file.pdf')
    ->send();

$printJob->id(); // the id number returned from the print server
```

> {note} Version 4.x is in a pre-release state currently. It is considered mostly stable, however breaking changes may be introduced as bugs are discovered and fixed. I will do my best however to prevent any breaking changes though.

## Supported Drivers

Laravel Printing currently only supports one two drivers currently. More drivers may be added in the future.

-   [PrintNode](https://printnode.com)
-   [CUPS](https://cups.org)
-   Custom: Configure your own custom driver

## Credits

-   [Randall Wilk](https://github.com/rawilk)
-   [All Contributors](https://github.com/rawilk/laravel-printing/contributors)
-   _Mike42_ for the [PHP ESC/POS Print Driver](https://github.com/mike42/escpos-php) library

Inspiration for the PrintNode API wrapper comes from:

-   [PrintNode/PrintNode-PHP](https://github.com/PrintNode/PrintNode-PHP)
-   [phatkoala/printnode](https://github.com/PhatKoala/PrintNode)

Inspiration for certain aspects of the API implementations comes from:

- [stripe-php](https://github.com/stripe/stripe-php)

## Disclaimer

This package is not affiliated with, maintained, authorized, endorsed or sponsored by Laravel or any of its affiliates.
