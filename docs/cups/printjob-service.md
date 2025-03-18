---
title: PrintJob Service
sort: 6
---

## Introduction

The `PrintJobService` can be used to create new print jobs and fetch existing jobs from the CUPS server.

All methods are callable from the `CupsClient` class.

```php
$printJobs = $client->printJobs->all();
```

See the [API Overview](/docs/laravel-printing/{version}/cups/api) for more information on interacting with the PrintNode API.

## Reference

### Methods

<hr>

#### all

_Collection<int, Rawilk\Printing\Api\Cups\Resources\PrintJob>_

Retrieve all print jobs reported by the CUPS server.

| param     | type                        | default |
| --------- | --------------------------- | ------- |
| `$params` | array\|null                 | null    |
| `$opts`   | null\|array\|RequestOptions | null    |

<hr>

#### create

_Rawilk\Printing\Api\Cups\Resources\PrintJob_

Create a new print job for CUPS to send to a physical printer.

| param         | type                                                                              | default |
| ------------- | --------------------------------------------------------------------------------- | ------- |
| `$pendingJob` | Rawilk\Printing\Api\Cups\PendingPrintJob\|Rawilk\Printing\Api\Cups\PendingRequest |         |
| `$opts`       | null\|array\|RequestOptions                                                       | null    |

We recommend using a `PendingPrintJob` object for the `$pendingJob` argument.

Example:

```php
use Rawilk\Printing\Api\Cups\PendingPrintJob;
use Rawilk\Printing\Api\Cups\Enums\ContentType;

$pendingJob = PendingPrintJob::make()
    ->setContent('hello world')
    ->setContentType(ContentType::Plain)
    ->setPrinter($printerUri)
    ->setTitle('My job title')
    ->setSource(config('app.name'));

$printJob = $client->printJobs->create($pendingJob);
```

<hr>

#### retrieve

_Rawilk\Printing\Api\Cups\Resources\PrintJob_

Retrieve a job from the CUPS server by its uri.

| param     | type                        | default | description   |
| --------- | --------------------------- | ------- | ------------- |
| `$uri`    | string                      |         | The job's uri |
| `$params` | array\|null                 | null    |               |
| `$opts`   | null\|array\|RequestOptions | null    |               |

<hr>

## PrintJob Resource

### Properties

<hr>

#### uri

_string_

The uri to the job. Alias to `$jobUri`.

<hr>

#### jobUri

_string_

The uri to the job.

<hr>

#### jobName

_?string_

The name of the job.

<hr>

#### jobPrinterUri

_string_

The uri to the printer the job was sent to.

<hr>

#### jobState

_int_

An integer representation of the job's state.

<hr>

#### dateTimeAtCreation

_?string_

The date/time the job was created and sent to the printer.

<hr>

### Methods

<hr>

#### state

_Rawilk\Printing\Api\Cups\Enums\JobState_

Returns an enum representation of the job's current state.

<hr>

#### printerName

_?string_

Returns the name of the printer the job was sent to.

<hr>
