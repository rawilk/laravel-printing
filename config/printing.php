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
