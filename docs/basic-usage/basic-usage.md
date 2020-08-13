---
title: Basic Usage
sort: 1
---

Most operations through this package can be done with the `Printing` facade.

## Listing printers
You can retrieve all available printers on your print server like this:

<x-code lang="php">
$printers = Printing::printers();

foreach ($printers as $printer) {
    echo $printer->name();
}
</x-code>

No matter which driver you use, each `$printer` object will be be an instance of `Rawilk\Printing\Contracts\Printer`. More info on the printer object [here](/laravel-printing/v1/basic-usage/printer).

## Finding a printer
You can find a specific printer if you know the printer's id:

<x-code lang="php">Printing::find($printerId);</x-code>

## Default printer
If you have a default printer id set in the config file, you can easily access the printer via the facade:

<x-code lang="php">
Printing::defaultPrinter(); // returns an instance of Rawilk\Printing\Contracts\Printer if the printer is found

// or for just the id
Printing::defaultPrinterId();
</x-code>

## Creating a new print job
You can send jobs to a printer on your print server by creating a new print task:

<x-code lang="php">
Printing::newPrintTask()
    ->printer($printerId)
    ->file('path_to_file.pdf')
    ->send();
</x-code>
