---
title: Printer Service
sort: 6
---

## Introduction

The `PrinterService` can be used to fetch printers associated with your PrintNode account.

All methods are callable from the `PrintNodeClient` class.

```php
$printers = $client->printers->all();
```

See the [API Overview](/docs/laravel-printing/{version}/printnode/api) for more information on interacting with the PrintNode API.

## Reference

### Methods

<hr>

#### all

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\Printer>_

Retrieve all printers associated with your PrintNode account.

| param     | type                        | default |
| --------- | --------------------------- | ------- |
| `$params` | array\|null                 | null    |
| `$opts`   | null\|array\|RequestOptions | null    |

<hr>

#### retrieve

_Rawilk\Printing\Api\PrintNode\Resources\Printer_

Retrieve a specific printer by ID from your PrintNode account.

| param     | type                        | default | description                    |
| --------- | --------------------------- | ------- | ------------------------------ |
| `$id`     | int                         |         | the printer's ID               |
| `$params` | array\|null                 | null    | not applicable to this request |
| `$opts`   | null\|array\|RequestOptions | null    |                                |

<hr>

#### retrieveSet

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\Printer>_

Retrieve a set of printers.

| param     | type                        | default | description     |
| --------- | --------------------------- | ------- | --------------- |
| `$ids`    | array                       |         | the printer IDs |
| `$params` | array\|null                 | null    |                 |
| `$opts`   | null\|array\|RequestOptions | null    |                 |

<hr>

#### printJobs

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\PrintJob>_

Retrieve all print jobs associated with a given printer. Pass an array for `$parentId` to retrieve print jobs for multiple printers.

| param       | type                        | default | description      |
| ----------- | --------------------------- | ------- | ---------------- |
| `$parentId` | int\|array                  |         | the printer's ID |
| `$params`   | array\|null                 | null    |                  |
| `$opts`     | null\|array\|RequestOptions | null    |                  |

<hr>

#### printJob

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\PrintJob>|Rawilk\Printing\Api\PrintNode\Resources\PrintJob_

Retrieve a single or set of print jobs associated with a given printer.

Pass an array for `$parentId` to retrieve print jobs for multiple printers. Pass an array for `$printJobId` to retrieve a set of print jobs.

| param         | type                        | default | description        |
| ------------- | --------------------------- | ------- | ------------------ |
| `$parentId`   | int\|array                  |         | the printer's ID   |
| `$printJobId` | int\|array                  |         | the print job's ID |
| `$params`     | array\|null                 | null    |                    |
| `$opts`       | null\|array\|RequestOptions | null    |                    |

<hr>

## Printer Resource

`Rawilk\Printing\Api\PrintNode\Resources\Printer`

A `Printer` represents a Printer attached to a `Computer` object in the PrintNode API.

### Properties

<hr>

#### id

_int_

The printer's ID.

<hr>

#### createTimestamp

_string_

Time and date the printer was first registered with PrintNode.

<hr>

#### computer

_Rawilk\Printing\Api\PrintNode\Resources\Computer_

The computer object the printer is attached to.

<hr>

#### name

_string_

The name of the printer.

<hr>

#### description

_?string_

The description of the printer reported by the client.

<hr>

#### capabilities

_?Rawilk\Printing\Api\PrintNode\Resources\Support\PrinterCapabilities_

The capabilities of the printer reported by the client.

<hr>

#### default

_bool_

Flag that indicates if this is the default printer for this computer.

<hr>

#### state

_string_

The state of the printer reported by the client.

<hr>

### Methods

<hr>

#### createdAt

_?CarbonInterface_

A date object representing the date and time the printer was first registered with PrintNode.

<hr>

#### copies

_int_

The maximum number of copies the printer supports.

<hr>

#### isColor

_bool_

Indicates if the printer is capable of color printing.

<hr>

#### canCollate

_bool_

Indicates true if the printer supports collation.

<hr>

#### media

_array_

An array of media names the printer driver supports. May be zero-length.

<hr>

#### bins

_array_

The paper tray names the printer driver supports. May be zero-length.

<hr>

#### trays

_array_

Alias for `bins()`.

<hr>

#### isOnline

_bool_

Indicates if the printer is considered to be online.

<hr>

### Methods

<hr>

#### printJobs

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\PrintJob>_

Fetch all print jobs that have been sent to the printer.

```php
$printJobs = $printer->printJobs();
```

| param     | type                        | default |
| --------- | --------------------------- | ------- |
| `$params` | null\|array                 | null    |
| `$opts`   | null\|array\|RequestOptions | null    |

<hr>

#### findPrintJob

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\PrintJob>|Rawilk\Printing\Api\PrintNode\Resources\PrintJob_

Find a specific print job that was sent to the printer. Pass an array for `$id` to find a set of print jobs.

```php
$printJob = $printer->findPrintJob(100);
```

| param     | type                        | default |
| --------- | --------------------------- | ------- |
| `$id`     | int\|array                  |         |
| `$params` | null\|array                 | null    |
| `$opts`   | null\|array\|RequestOptions | null    |

### Static Methods

<hr>

#### all

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\Printer>_

Retrieve all printers.

```php
$printers = Printer::all();
```

| param     | type                        | default |
| --------- | --------------------------- | ------- |
| `$params` | null\|array                 | null    |
| `$opts`   | null\|array\|RequestOptions | null    |

<hr>

#### retrieve

_Rawilk\Printing\Api\PrintNode\Resources\Printer_

Retrieve a printer with a given id.

```php
$printer = Printer::retrieve(100);
```

| param     | type                        | default |
| --------- | --------------------------- | ------- |
| `$id`     | int                         |         |
| `$params` | null\|array                 | null    |
| `$opts`   | null\|array\|RequestOptions | null    |
