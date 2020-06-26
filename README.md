# Direct printing for Laravel apps

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rawilk/laravel-printing.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-printing)
![Tests](https://github.com/rawilk/laravel-printing/workflows/Tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/rawilk/laravel-printing.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-printing)


Laravel Printing allows your application to directly send PDF documents or raw text directly from a remote server
to a printer on your local network. Receipts can also be printed by first generating the raw text via the `Rawilk\Printing\Receipts\ReceiptPrinter` class, and then sending the text as a raw print job via the `Printing` facade.

```php
Printing::newPrintTask()
    ->printer($printerId)
    ->file('path_to_file.pdf')
    ->send();
```

Supported Print Drivers:

- PrintNode: https://printnode.com

## Installation

You can install the package via composer:

```bash
composer require rawilk/laravel-printing
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Rawilk\Printing\PrintingServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Driver
    |--------------------------------------------------------------------------
    |
    | Supported: `printnode`
    |
    */
    'driver' => env('PRINTING_DRIVER', 'printnode'),

    /*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    |
    | Configuration for each driver.
    |
    */
    'drivers' => [
        'printnode' => [
            'key' => env('PRINT_NODE_API_KEY'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Printer Id
    |--------------------------------------------------------------------------
    |
    | If you know the id of a default printer you want to use, enter it here.
    |
    */
    'default_printer_id' => null,

    /*
    |--------------------------------------------------------------------------
    | Receipt Printer Options
    |--------------------------------------------------------------------------
    |
    */
    'receipts' => [
        /*
         * How many characters fit across a single line on the receipt paper.
         * Adjust according to your needs.
         */
        'line_character_length' => 45,

        /*
         * The width of the print area in dots.
         * Adjust according to your needs.
         */
        'print_width' => 550,

        /*
         * The height (in dots) barcodes should be printed normally.
         */
        'barcode_height' => 64,

        /*
         * The width (magnification) each barcode should be printed in normally.
         */
        'barcode_width' => 2,
    ],
];
```

## Usage

### Listing printers
``` php
Printing::printers();
```

### Finding a specific printer
```php
Printing::find($printerId);
```

### Default printer
If you have a default printer id set in the config, you can easily access the printer via the facade:
```php
Printing::defaultPrinter();
Printing::defaultPrinterId();
```

### Creating a new print job

Basic printing:
```php
Printing::newPrintTask()
    ->printer($printerId)
    ->file('path_to_file.pdf')
    ->send();
```

With options:
```php
Printing::newPrintTask()
    ->printer($printerId)
    ->file('path_to_file.pdf')
    ->jobTitle('my job title')
    ->option('fit_to_page', true) // fit_to_page if using PrintNode
    ->copies(2)
    ->tray('Tray 1') // check if your driver and printer supports this
    ->option()
    ->send();
```

More PrintNode options can be found here: https://www.printnode.com/en/docs/api/curl#printjob-options

## Receipt Printing
Receipt printing can be done if you have a receipt printer by using the `Rawilk\Printing\Receipts\ReceiptPrinter` class.
The `ReceiptPrinter` class will return a string that can be used to `raw` print if using a driver like `printnode`.

```php
$text = (string) (new ReceiptPrinter)
    ->centerAlign()
    ->text('My heading')
    ->leftAlign()
    ->line()
    ->twoColumnText('Item 1', '2.00')
    ->twoColumnText('Item 2', '4.00')
    ->feed(2)
    ->centerAlign()
    ->barcode('1234')
    ->cut();

Printing::newPrintTask()
    ->printer($printerId)
    ->content($text) // content will be base64_encoded if using PrintNode
    ->send();
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email randall@randallwilk.dev instead of using the issue tracker.

## Credits

- [Randall Wilk](https://github.com/rawilk)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
