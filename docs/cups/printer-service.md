---
title: Printer Service
sort: 5
---

## Introduction

The `PrinterService` can be used to fetch printers installed on your CUPS server.

All methods are callable from the `CupsClient` class.

```php
$printers = $client->printers->all();
```

See the [API Overview](/docs/laravel-printing/{version}/cups/api) for more information on interacting with the PrintNode API.

## Reference

### Methods

<hr>

#### all

_Collection<int, Rawilk\Printing\Api\Cups\Resources\Printer>_

Retrieve all printers associated installed on the CUPS server.

| param     | type                        | default |
| --------- | --------------------------- | ------- |
| `$params` | array\|null                 | null    |
| `$opts`   | null\|array\|RequestOptions | null    |

<hr>

#### retrieve

Retrieve a printer from the server.

_Rawilk\Printing\Api\Cups\Resources\Printer_

| param     | type                        | default | description       |
| --------- | --------------------------- | ------- | ----------------- |
| `$uri`    | string                      |         | The printer's uri |
| `$params` | array\|null                 | null    | Unused for now    |
| `$opts`   | null\|array\|RequestOptions | null    |                   |

<hr>

#### printJobs

_Collection<int, Rawilk\Printing\Api\Cups\Resources\PrintJob>_

Retrieve all print jobs for a given printer.

| param        | type                        | default | description       |
| ------------ | --------------------------- | ------- | ----------------- |
| `$parentUri` | string                      |         | The printer's uri |
| `$params`    | array\|null                 | null    |                   |
| `$opts`      | null\|array\|RequestOptions | null    |                   |

<hr>

## Printer Resource

`Rawilk\Printing\Api\Cups\Resources\Printer`

A `Printer` represents a Printer installed on a CUPS server.

### Properties

<hr>

#### uri

_string_

The printer's uri. Alias to `$printerUriSupported`.

<hr>

#### printerUriSupported

_string_

The printer's uri.

<hr>

#### printerState

_int_

An integer representation of the printer's status.

<hr>

#### printerName

_string_

The name of the printer.

<hr>

#### mediaSourceSupported

_array_

The media (trays) the printer supports.

<hr>

#### printerInfo

_?string_

A description of the printer, if provided.

<hr>

#### printerStateReasons

_array_

A more detailed list of the printer's status.

<hr>

### Methods

<hr>

#### capabilities

_array_

Returns an array of the printer's capabilities.

<hr>

#### state

_?Rawilk\Printing\Api\Cups\Enums\PrinterState_

Returns an enum representing the printer's current state.

<hr>

#### stateReasons

_Collection<int, Rawilk\Printing\Api\Cups\Enums\PrinterStateReason>_

If any reasons are provided for the printer's state, this will return a collection of enums that represent the reason for the printer's state.

<hr>

#### isOnline

_bool_

Indicates if the printer is considered to be online.

<hr>

#### trays

_array_

Returns an array of the printer's reported trays.
