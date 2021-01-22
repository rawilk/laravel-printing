---
title: Custom Drivers
sort: 4
---

**Since: 1.3.0**

## Introduction

If you need to use a driver that isn't supported by the package, you can easily add your own custom driver.
Adding a custom driver will require you to add the driver's config to the `drivers` in the config file, and
to extend the printing factory in a service provider.

## Configuring a Custom Driver

Add your custom driver configuration under `drivers` in `config/printing`. The minimum required for your driver config
is a `driver` key.

```php
'driver' => 'my_custom_driver',

'drivers' => [
    ...
    'my_custom_driver' => [
        'driver' => 'custom', // This value is required
        // any other configuration needed
    ],
],
```

You can change `custom` and `my_custom_driver` to whatever you want. Any data you specify in the configuration
of your custom driver will be passed to the closure you provide to the printing factory when extending it.

## Defining a Custom Driver

Once you have your custom driver configuration defined, you need to tell the printing package how to create it. This
is done by extending the print factory used by this package. In a service provider, you can do it like this:

```php
public function register(): void
{
    $this->app['printing.factory']->extend('custom', function (array $config) {
        return new MyCustomDriver($config);    
    });
}
```

The value you pass in as the first parameter needs to match what you defined as **driver** in your custom
driver's configuration earlier.
