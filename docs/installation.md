---
title: Installation & Setup
sort: 3
---

## Installation

laravel-printing can be installed via composer:

```bash
composer require rawilk/laravel-printing
```

## Publishing the config file

You may publish the config file like this:

```bash
php artisan vendor:publish --provider="Rawilk\Printing\PrintingServiceProvider" --tag="config"
```

This is the default content of `config/printing.php`:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Driver
    |--------------------------------------------------------------------------
    |
    | Supported: `printnode`, `cups`
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
        'cups' => [
            'ip' => env('CUPS_SERVER_IP'),
            'username' => env('CUPS_SERVER_USERNAME'),
            'password' => env('CUPS_SERVER_PASSWORD'),
            'port' => env('CUPS_SERVER_PORT', 631),
        ],

        /*
         * Add your custom drivers here:
         *
         * 'custom' => [
         *      'driver' => 'custom_driver',
         *      // other config for your custom driver
         * ],
         */
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

## Setting up a print driver

To print with laravel printing, you must setup a supported print driver.

### PrintNode
- You must sign up for an account at PrintNode. You can sign up here: [https://app.printnode.com/app/login/register](https://app.printnode.com/app/login/register)
- Review the [requirements](/docs/laravel-printing/{version}/requirements#printnode) for the PrintNode driver
- Enter your api key in your `.env` file: `PRINT_NODE_API_KEY=your-api-key`

### CUPS
- Review the [requirements](/docs/laravel-printing/{version}/requirements#cups) for the CUPS driver
- If using a remote server, enter your remote server credentials in the `.env` file (see config).
