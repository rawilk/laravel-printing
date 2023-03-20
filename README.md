# Printing for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rawilk/laravel-printing.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-printing)
![Tests](https://github.com/rawilk/laravel-printing/workflows/Tests/badge.svg?style=flat-square)
[![Total Downloads](https://img.shields.io/packagist/dt/rawilk/laravel-printing.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-printing)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/rawilk/laravel-printing?style=flat-square)](https://packagist.org/packages/rawilk/laravel-printing)
[![License](https://img.shields.io/github/license/rawilk/laravel-printing?style=flat-square)](https://github.com/rawilk/laravel-printing/blob/main/LICENSE.md)

![social image](https://banners.beyondco.de/Printing%20for%20Laravel.png?theme=light&packageManager=composer+require&packageName=rawilk%2Flaravel-printing&pattern=parkayFloor&style=style_1&description=Direct+printing+for+Laravel+apps.&md=1&showWatermark=0&fontSize=100px&images=printer)

Printing for Laravel allows your application to directly send PDF documents or raw text directly from a remote server
to a printer on your local network. Receipts can also be printed by first generating the raw text via the `Rawilk\Printing\Receipts\ReceiptPrinter` class, and then sending the text as a raw print job via the `Printing` facade.

```php
$printJob = Printing::newPrintTask()
    ->printer($printerId)
    ->file('path_to_file.pdf')
    ->send();

$printJob->id(); // the id number returned from the print server
```

Supported Print Drivers:

-   PrintNode: https://printnode.com
-   CUPS: https://cups.org
-   Custom: Configure your own custom driver

## Documentation:

For documentation, please visit: https://randallwilk.dev/docs/laravel-printing

## Installation

You can install the package via composer:

```bash
composer require rawilk/laravel-printing
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="printing-config"
```

The contents of the default configuration file can be found here: https://github.com/rawilk/laravel-printing/blob/main/config/printing.php

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email randall@randallwilk.dev instead of using the issue tracker.

## Credits

-   [Randall Wilk](https://github.com/rawilk)
-   [All Contributors](../../contributors)
-   _Mike42_ for the [PHP ESC/POS Print Driver](https://github.com/mike42/escpos-php) library

Inspiration for the PrintNode API wrapper comes from:

-   [PrintNode/PrintNode-PHP](https://github.com/PrintNode/PrintNode-PHP)
-   [phatkoala/printnode](https://github.com/PhatKoala/PrintNode)

## Disclaimer

This package is not affiliated with, maintained, authorized, endorsed or sponsored by Laravel or any of its affiliates.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
