---
title: Introduction
sort: 1
---

Laravel Printing allows your application to directly send PDF documents or raw text directly from a remote server to a printer on your local network.
Receipts can also be printed by first generating the raw text via the `Rawilk\Printing\Receipts\ReceiptPrinter` class, and then sending the text as a
raw print job via the `Printing` facade.

Here's a simple example of what you can do with this package:

```php
$printJob = Printing::newPrintTask()
    ->printer($printerId)
    ->file('path_to_file.pdf')
    ->send();

$printJob->id(); // the id number returned from the print server
```

## Supported Drivers

Laravel Printing currently only supports one driver at this time, but support for more drivers is planned for the future.

- [PrintNode](https://printnode.com)

## Credits

- [Randall Wilk](https://github.com/rawilk)
- [All Contributors](https://github.com/rawilk/laravel-printing/contributors)
- _Mike42_ for the [PHP ESC/POS Print Driver](https://github.com/mike42/escpos-php) library
