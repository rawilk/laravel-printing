<?php

declare(strict_types=1);

use Rawilk\Printing\Enums\PrintDriver;

return [
    /*
    |--------------------------------------------------------------------------
    | Driver
    |--------------------------------------------------------------------------
    |
    | Supported: `printnode`, `cups`
    |
    */
    'driver' => env('PRINTING_DRIVER', PrintDriver::PrintNode->value),

    /*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    |
    | Configuration for each driver.
    |
    */
    'drivers' => [
        PrintDriver::PrintNode->value => [
            'key' => env('PRINT_NODE_API_KEY'),
        ],

        PrintDriver::Cups->value => [
            'ip' => env('CUPS_SERVER_IP'),
            'username' => env('CUPS_SERVER_USERNAME'),
            'password' => env('CUPS_SERVER_PASSWORD'),
            'port' => (int) env('CUPS_SERVER_PORT'),
            'secure' => env('CUPS_SERVER_SECURE'),
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

    /*
    |--------------------------------------------------------------------------
    | Printing Logger
    |--------------------------------------------------------------------------
    |
    | This setting defines which logging channel will be used by this package
    | to write log messages. You are free to specify any of your logging
    | channels listed inside the "logging" configuration file.
    |
    */
    'logger' => env('PRINTING_LOGGER'),
];
