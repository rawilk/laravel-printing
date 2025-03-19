---
title: Basic Usage
sort: 1
---

## Introduction

Most operations through this package can be done with the `Printing` facade. Everything documented on this page will be the same regardless of the [driver](/docs/laravel-printing/{version}/installation#user-content-setting-up-a-print-driver) you are using.

## Listing printers

You can retrieve all available printers on your print server like this:

```php
use Rawilk\Printing\Facades\Printing;

$printers = Printing::printers();

foreach ($printers as $printer) {
    echo $printer->name();
}
```

No matter which driver you use, each `$printer` object will be an instance of `Rawilk\Printing\Contracts\Printer`. More info on the printer object [here](/docs/laravel-printing/{version}/basic-usage/printer).

## Finding a printer

You can find a specific printer if you know the printer's id:

```php
Printing::printer($printerId);
```

## Default printer

If you have a default printer id set in the config file, you can easily access the printer via the facade:

```php
// returns an instance of Rawilk\Printing\Contracts\Printer if the printer is found
Printing::defaultPrinter();

// or for just the id
Printing::defaultPrinterId();
```

> {note} This will only work for the default driver. Any calls to a different driver at runtime (i.e. `Printing::driver(...)->defaultPrinter())` will not work.

## Creating a new print job

You can send jobs to a printer on your print server by creating a new print task:

```php
Printing::newPrintTask()
    ->printer($printerId)
    ->file('path_to_file.pdf')
    ->send();
```
