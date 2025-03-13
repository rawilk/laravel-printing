---
title: Upgrade Guide
sort: 3
---

## Upgrading To 4.0 From 3.x

Upgrading from an earlier version? Check out the previous [upgrade guide](/docs/laravel-printing/v3/upgrade) first.

While I attempt to document every possible breaking change, I may have missed some things. Make sure to thoroughly test your integration before deploying when upgrading.

### Updating dependencies

**Likelihood Of Impact: Medium**

You should update the following dependencies in your application's `composer.json` file if you haven't already:

- `laravel/framework` to `^10.0`

> {note} The Laravel version `10.x` is the minimum version your application must be running. This package supports the latest `12.x` Laravel version as well.

#### PHP Version

**Likelihood Of Impact: Medium**

The server your application is running on must be using  a minimum of php 8.2.

### Printer Interface

**Likelihood Of Impact: Low**

If you have a custom driver with a Printer object that implements the `Printer` interface, you must now implement the `Arrayable` and `JsonSerializable` interfaces on your Printer object as well.

### PrintTask Interface

**Likelihood Of Impact: Low**

If you have a custom driver, the `option()` method signature on the `PrintTask` interface has changed to allow support for passing in enums for option keys. Your signature should now match this:

```php
public function option(BackedEnum|string $key, $value): self;
```

### PrintJob Interface

**Likelihood Of Impact: Low**

If you have a custom driver, the `date()` method signature on the `PrintJob` interface has changed. Your print job object must also implement the `Arrayable` and `JsonSerializable` interfaces as well.

Here is the updated date method signature for `PrintJob`:

```php
public function date(): ?CarbonInterface;
```

### Exceptions

**Likelihood Of Impact: Low**

Every custom exception class thrown by the package now either extends the `Rawilk\Printing\Exceptions\PrintingException` base exception and/or implements the `Rawilk\Printing\Exceptions\ExceptionInterface` interface. 

This shouldn't really affect anything, however you may now listen for that base exception or interface in a `try/catch` instead to catch any exceptions the package will throw.

#### PrintNodeApiRequestFailed Exception

**Likelihood Of Impact: Low**

The `Rawilk\Printing\Exceptions\PrintNodeApiRequestFailed` Exception has been deprecated in favor of moving that exception closer to the api implementation for PrintNode. It will be removed in a future version.

The new exception is now located at: `Rawilk\Printing\Api\PrintNode\Exceptions\PrintNodeApiRequestFailed`

### PrintNode API Resources

**Likelihood Of Impact: Low**

Every Entity class under the `Rawilk\Printing\Api\PrintNode\Entity` has been removed. These classes have all been refactored to extend a new base `Rawilk\Printing\Api\PrintNode\PrintNodeObject` base class, and each of the resource classes now live in the `Rawilk\Printing\Api\PrintNode\Resources` namespace.

Any custom collection classes, such as the `Printers` collection have been removed all-together in favor of plain Laravel collections.

### PrintNode ContentType Class

**Likelihood Of Impact: High**

The `ContentType` class from the PrintNode driver has been removed in favor of an enum instead. If you are setting the content type for a print job with the PrintNode driver and reference this class, be sure to update your references to the following:

```php
use Rawilk\Printing\Api\PrintNode\Enums\ContentType;

$contentType = ContentType::PdfBase64;
```

### PrintNode API Key

**Likelihood Of Impact: Medium**

First off, the api key is not required to be filled within the `config/printing.php` driver config for PrintNode anymore. You may either use an empty array for the `printnode` config, or set the `key` configuration key to `null`.

If you are setting the API used to make requests to PrintNode at runtime, you will need to update your code. Setting the api key via config is still supported, however and remains unchanged.

There are now actually a few different ways you can use a specific api key for a single request. The first way involves passing the api key through as a request option.

```php
Printing::newPrintTask()
    ->printer($printerId)
    ->content('hello world')
    ->send(['api_key' => 'my-key']);

// Also works with other method calls
Printing::printer($printerId, [], ['api_key' => 'my-key']);
```

> {note} You cannot utilize php's named arguments when passing in extra parameters like this because these arguments do not exist on the underlying Printing service class method signatures.

Another option you have for dynamically setting the api key is by setting it on the `PrintNode` api class. 

```php
use Rawilk\Printing\Api\PrintNode\PrintNode;

PrintNode::setApiKey('my-key');
```

> {note} An api key set in the `config/printing.php` configuration for `printnode` will take precedence over this method. Set the config value to `null` to avoid any issues if  you are doing this.

One other way to update the api key is by setting it on the driver itself. This is the least recommended way of doing it, but it's still an option.

```php
Printing::driver('printnode')->getDriver()->setApiKey('my-key');
```

### PrintNode API Class

**Likelihood Of Impact: Low**

Unless your application is interacting with the PrintNode api wrapper directly, this won't affect you. The PrintNode api integration has been completely refactored in this version, and all the method calls to the api have been removed from this class.

Each resource is now fetched or created from service classes that are referenced by the `PrintNodeClient` class.

### PrintNode Driver Printer

**Likelihood Of Impact: Low**

The constructor of the `Rawilk\Printing\Drivers\PrintNode\Entity\Printer` printer now accepts the Printer resource class instead from the PrintNode api wrapper. It has also been set to `readonly` on the class. The resource class will also now be returned when the `printer()` method is called from this object.

### PrintNode Driver PrintJob

**Likelihood Of Impact: Low**

The constructor of the `Rawilk\Printing\Drivers\PrintNode\Entity\PrintJob` print job now accepts the PrintJob resource class instead from the PrintNode api wrapper. It has also been set to `readonly` on the class. The resource class will also now be returned when the `job()` method is called from this object.

### Cups Driver Printer

**Likelihood Of Impact: Low**

The constructor of the `Rawilk\Printing\Drivers\Cups\Entity\Printer` now accepts the Printer resource class from the Cups api wrapper.

### Cups Driver PrintJob

**Likelihood Of Impact: Low**

The constructor of the `Rawilk\Printing\Drivers\Cups\Entity\PrintJob` now accepts the PrintJob resource class from the Cups api wrapper.

### Cups Driver PrintTask

**Likelihood Of Impact: Low**

The `Rawilk\Printing\Drivers\Cups\PrintTask` class now wraps the new `CupsClient` api wrapper, and defers all resource calls to it.

### Cups API Class

**Likelihood Of Impact: Low**

Unless your application is interacting with the Cups api wrapper directly, this won't affect you. The Cups api integration has been completely refactored in this version, and all the method calls to the api have been removed from this class.

Each resource is now fetched or created from service classes that are referenced by the `CupsClient` class.

### Miscellaneous

I also encourage you to view the changes in the `rawilk/laravel-printing` [GitHub repository](https://github.com/rawilk/laravel-printing). There may be changes not documented here that affect your integration. You can easily view all changes between this version and version 3.x with the [GitHub comparison tool](https://github.com/rawilk/laravel-printing/compare/3.0.5...4.0.0).
