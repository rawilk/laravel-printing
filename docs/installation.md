---
title: Installation & Setup
sort: 4
---

## Installation

laravel-printing can be installed via composer:

```bash
composer require rawilk/laravel-printing
```

## Publishing the config file

You may publish the config file like this:

```bash
php artisan vendor:publish --tag="printing-config"
```

The contents of the default configuration file can be found here: [https://github.com/rawilk/laravel-printing/blob/{branch}/config/printing.php](https://github.com/rawilk/laravel-printing/blob/{branch}/config/printing.php)

## Setting up a print driver

To print with laravel printing, you must either setup a supported driver, or write and configure a custom driver.

- For PrintNode: [PrintNode Overview](/docs/laravel-printing/{version}/printnode/overview)
- For CUPS: [CUPS Overview](/docs/laravel-printing/{version}/cups/overview)
- For Custom Drivers: [Custom Drivers](/docs/laravel-printing/{version}/advanced-usage/custom-drivers)
