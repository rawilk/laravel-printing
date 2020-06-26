# Direct printing for Laravel apps

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rawilk/laravel-printing.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-printing)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/rawilk/laravel-printing/run-tests?label=tests)](https://github.com/rawilk/laravel-printing/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/rawilk/laravel-printing.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-printing)


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

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
];
```

## Usage

``` php
$printing = new Rawilk\Printing;
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
