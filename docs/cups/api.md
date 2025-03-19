---
title: API
sort: 4
---

## Introduction

The functionality provided by the CUPS driver should work for most applications, however you may interact with the cups implementation directly if necessary.

To get started, you will need to resolve the client out of the container:

```php
use Rawilk\Printing\Api\Cups\CupsClient;

$client = app(CupsClient::class, [
    'config' => [
        'ip' => 'your-ip',
        'username' => 'your-username',
        'password' => 'your-password',
        'port' => 631,
        'secure' => true,
    ],
]);
```

> {note} Providing the server credentials to the constructor here is optional, however it wil need to be set on the client manually before a request is made. The client **will not** resolve your credentials from the package's config file.

## Setting Server Credentials

There are a few ways to set your CUPS server credentials for requests on the client. The first way is shown above in the [Introduction](#user-content-introduction).

Another way is to set it u sing request options when calling a method on a [Service](#user-content-services).

```php
$client->printers->retrieve($printerId, opts: ['ip' => 'your-ip']);
```

You may also choose to set the credentials on the `Cups` class itself. When the client does not detect a certain credential on the request, it will defer to this class for the value. This is typically done in a service provider in your application.

```php
use Rawilk\Printing\Api\Cups\Cups;

Cups::setIp('your-ip');
Cups::setAuth('your-username', 'your-password');
Cups::setPort(631);
Cups::setSecure(true);
```

> {note} Any credential set either on the client itself or passed through as a request option (via `$opts` arguments) will take precedence over this.

> {tip} You can also set credentials globally for CUPS like this when using the `Printing` facade. Keep in mind though the package configuration values will take precedence over this, unless you set them to `null` in the config.

## Services

The CUPS implementation for this package splits requests to the server into service classes, depending on the resource you're creating or retrieving.

For example, to retrieve all printers, you would use the `printers` service class on the client.

```php
$client->printers->all();
```

More information about each service can be found on that service's doc page.

## Resources

A resource class represents some kind of resource retrieved from the CUPS server, such as a printer or print job. When the `Printing` facade is used, the CUPS entity objects will contain a reference to their relevant CUPS resource objects as well.

All the resource objects supported by the package can be found here: https://github.com/rawilk/laravel-printing/tree/{branch}/src/Api/Cups/Resources

## Request Options

Most requests performed on the CUPS client accept request options, which can be used to set your server credentials on the request. Any time request options are supported, you can specify them through the `$opts` argument on method calls.

We recommend using an array, as the package will parse through that and create the `RequestOptions` object for you.

```php
$client->printJobs->create([...], opts: [
    'ip' => 'your-ip',
    'username' => 'your-username',
    'password' => 'your-password',
    'port' => 631,
    'secure' => true,
]);
```

> {tip} The `$opts` argument is accepted in most method calls to the api when using the `Printing` facade as well.
