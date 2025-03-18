---
title: Entities
sort: 2
---

## Introduction

The `Printer` and `PrintJob` entities returned from the `PrintNode` driver offer some additional functionalities to the interfaces they implement.

## Printer

`Rawilk\Printing\Drivers\PrintNode\Entity\Printer`

Here is a basic reference to the additional information provided by a PrintNode Printer object. See [Printer](/docs/laravel-printing/{version}/basic-usage/printer) for more information about the base printer object.

### Methods
<hr>

#### id

_int_

The ID of a printer retrieved from PrintNode will be an integer.

<hr>

#### printer

_Rawilk\Printing\Api\PrintNode\Resources\Printer_

Returns an instance of the printer object retrieved from the PrintNode API.

<hr>

#### printerCapabilities

Returns an instance of a `Rawilk\Printing\Api\PrintNode\Resources\Support\PrinterCapabilities` object retrieved from the PrintNode API.

<hr>

#### jobs

Retrieve the print jobs sent to the printer instance. This driver accepts the additional `$params` and `$opts` parameters for this method.

The `$params` argument can be used to limit the results and sort them. Here are the supported values:

```php
$params = [
    'limit' => 3,
    'after' => 1, // a job id to offset the results by for pagination
    'dir' => 'asc', // or 'desc'
];
```

The `$opts` argument isn't really necessary here, since the printer instance will already have a reference to the api key used to retrieve it.

Both additional arguments accepted by this driver are optional for this method call.

<hr>

## PrintJob

`Rawilk\Printing\Drivers\PrintNode\Entity\PrintJob`

Here is a basic reference to the additional information provided by a PrintNode PrintJob object. See [PrintJob](/docs/laravel-printing/{version}/basic-usage/print-job) for more information about the base print job object.

### Methods
<hr>

#### id

_int_

The ID of a print job retrieved from PrintNode will be an integer.

<hr>

#### job

_Rawilk\Printing\Api\PrintNode\Resources\PrintJob_

Returns an instance of the print job object that was retrieved from the PrintNode API.

<hr>

### Properties
<hr>

#### printer

_Rawilk\Printing\Drivers\PrintNode\Entity\Printer_

If the API response retrieved a printer object, this property will be a reference to it.
