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

To print with laravel printing, you must set up a supported print driver.

### PrintNode

-   You must sign up for an account at PrintNode. You can sign up here: [https://app.printnode.com/app/login/register](https://app.printnode.com/app/login/register)
-   Review the [requirements](/docs/laravel-printing/{version}/requirements#printnode) for the PrintNode driver
-   Enter your api key in your `.env` file: `PRINT_NODE_API_KEY=your-api-key`

### CUPS

-   Review the [requirements](/docs/laravel-printing/{version}/requirements#cups) for the CUPS driver
-   If using a remote server, enter your remote server credentials in the `.env` file (see config)
-   In the terminal, run: `composer require smalot/cups-ipp`

#### Job Names

If you're having an issue sending the name of the job to CUPS, try changing `JobPrivateValues default` to `JobPrivateValues none` in `/etc/cups/cupsd.conf`.
