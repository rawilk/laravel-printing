---
title: PrintJob Service
sort: 7
---

## Introduction

The `PrintJobService` can be used to create new print jobs and fetch existing jobs on your PrintNode account.

All methods are callable from the `PrintNodeClient` class.

```php
$printJobs = $client->printJobs->all();
```

See the [API Overview](/docs/laravel-printing/{version}/printnode/api) for more information on interacting with the PrintNode API.

## Reference

### Methods
<hr>

#### all

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\PrintJob>_

Retrieve all print jobs associated with a PrintNode account.

| param | type | default | 
| --- | --- | --- |
| `$params` | array\|null | null |
| `$opts` | null\|array\|RequestOptions | null |

<hr>

#### create

_Rawilk\Printing\Api\PrintNode\Resources\PrintJob_

Create a new print job for PrintNode to send to a physical printer. Note: although the `$params` argument accepts an array, it is recommended to send through a `PendingPrintJob` object instead.

| param | type                        | default | 
| --- |-----------------------------| --- |
| `$params` | array\|PendingPrintJob      | null |
| `$opts` | null\|array\|RequestOptions | null |

Example:

```php
use Rawilk\Printing\Api\PrintNode\PendingPrintJob;
use Rawilk\Printing\Api\PrintNode\Enums\ContentType;

$pendingJob = PendingPrintJob::make()
    ->setContent('hello world')
    ->setContentType(ContentType::RawBase64)
    ->setPrinter($printerId)
    ->setTitle('My job title')
    ->setSource(config('app.name'));

$printJob = $client->printJobs->create($pendingJob);
```

<hr>

#### retrieve

_Rawilk\Printing\Api\PrintNode\Resources\PrintJob_

Retrieve a print job by ID.

| param | type                        | default | description                   |
| --- |-----------------------------| --- |-------------------------------|
| `$id` | int                         | | the print job's ID |
| `$params` | array\|null                 | null | not applicable to this request |
| `$opts` | null\|array\|RequestOptions | null |                               |

<hr>

#### retrieveSet

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\PrintJob>_

Retrieve a specific set of print jobs.

| param     | type                        | default | description       |
|-----------|-----------------------------| --- |-------------------|
| `$ids`    | array                       | | the print job IDs |
| `$params` | array\|null                 | null |                   |
| `$opts`   | null\|array\|RequestOptions | null |                   |

<hr>

#### states

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\PrintJobState>_

Retrieve all print job states for an account.

Note: If a `limit` is passed in with `$params`, it applies to the amount of print jobs to retrieve states for. For example, if there are 3 print jobs with 5 states each, and a limit of 2 is specified, a total of 10 print job states will be received.

| param     | type                        | default |
|-----------|-----------------------------| --- |
| `$params` | array\|null                 | null |
| `$opts`   | null\|array\|RequestOptions | null |

<hr>

#### statesFor

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\PrintJobState>_

Retrieve the print job states for a given print job.

| param     | type                        | default | description |
|-----------|-----------------------------| --- | --- |
| `$parentId` | int\|array | | the print job's ID |
| `$params` | array\|null                 | null | |
| `$opts`   | null\|array\|RequestOptions | null | |

<hr>

#### cancelMany

_array_

Cancel (delete) a set of pending print jobs. Method will return an array of affected IDs. Omit or use an empty array of `$ids` to delete all jobs.

| param     | type                        | default |
|-----------|-----------------------------| --- |
| `$ids` | array | |
| `$params` | array\|null                 | null |
| `$opts`   | null\|array\|RequestOptions | null |

<hr>

#### cancel

_array_

Cancel (delete) a given pending print job. Method will return an array of affected IDs.

| param    | type                        | default |
|----------|-----------------------------| --- |
| `$id` | int                         | |
| `$params` | array\|null                 | null |
| `$opts`  | null\|array\|RequestOptions | null |

<hr>

## PrintJob Resource

`Rawilk\Printing\Api\PrintNode\Resources\PrintJob`

A `PrintJob` represents a print job in the PrintNode API.

### Properties
<hr>

#### id

_int_

The print job's ID.

<hr>

#### createTimestamp

_string_

Time and date the print job was created.

<hr>

#### printer

_Rawilk\Printing\Api\PrintNode\Resources\Printer_

The printer the job was sent to.

<hr>

#### title

_string_

The title of the print job.

<hr>

#### contentType

_string_

The content type of the print job.

<hr>

#### source

_string_

A string that describes the origin of the print job.

<hr>

#### expireAt

_?string_

The time at which the print job expires.

<hr>

#### state

_string_

The current state of the print job.

<hr>

### Methods
<hr>

#### createdAt

_?CarbonInterface_

A date object representing the date and time the print job was created.

<hr>

#### expiresAt

_?CarbonInterface_

A date object representing the date and time the print job will expire.

<hr>

#### delete

_Rawilk\Printing\Api\PrintNode\Resources\PrintJob_

Delete (cancel) the print job.

| param | type                        | default |
| --- |-----------------------------|---------|
| `$params` | array\|null                 | null    |
| `$opts` | null\|array\|RequestOptions | null    |

<hr>

#### getStates

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\PrintJobState>_

Get all the states that PrintNode has reported for the print job.

| param | type                        | default |
| --- |-----------------------------|---------|
| `$params` | array\|null                 | null    |
| `$opts` | null\|array\|RequestOptions | null    |

<hr>

### Static Methods
<hr>

#### create

_Rawilk\Printing\Api\PrintNode\Resources\PrintJob_

Create and send a new print job through the PrintNode API.

| param | type | default |
| --- | --- | --- |
| `$params` | array\|PendingPrintJob | |
| `$opts` | null\|array\|RequestOptions | null |

Example:

```php
use Rawilk\Printing\Api\PrintNode\PendingPrintJob;
use Rawilk\Printing\Api\PrintNode\Enums\ContentType;
use Rawilk\Printing\Api\PrintNode\Resources\PrintJob;

$pendingJob = PendingPrintJob::make()
    ->setContent('hello world')
    ->setContentType(ContentType::RawBase64)
    ->setPrinter($printerId)
    ->setTitle('My job title')
    ->setSource(config('app.name'));

$printJob = PrintJob::create($pendingJob);
```

<hr>

#### all

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\PrintJob>_

Retrieve all print jobs.

```php
$printJobs = PrintJob::all();
```

| param | type | default |
| --- | --- |---------|
| `$params` | null\|array | null    |
| `$opts` | null\|array\|RequestOptions | null    |

<hr>

#### retrieve

_Rawilk\Printing\Api\PrintNode\Resources\PrintJob_

Retrieve a print job with a given id.

```php
$printJob = PrintJob::retrieve(100);
```

| param | type | default |
| --- | --- |---------|
| `$id` | int | |
| `$params` | null\|array | null    |
| `$opts` | null\|array\|RequestOptions | null    |

<hr>

## PrintJobState Resource

`Rawilk\Printing\Api\PrintNode\Resources\PrintJobState`

A `PrintJobState` represents a state that a `PrintJob` was in at a given time in the PrintNode API.

### Properties
<hr>

#### printJobId

_int_

The ID of the print job the state is for.

<hr>

#### state

_string_

The state code for the print job.

<hr>

#### message

_string_

Additional information about the state.

<hr>

#### clientVersion

_?string_

If the state was generated by a PrintNode Client, this is the Client's version; otherwise `null`.

<hr>

#### createTimestamp

_string_

If the state was generated by a PrintNodeClient, this is the timestamp at which the state was reported to the PrintNode server. Otherwise, it is the timestamp at which the PrintNode Server generated the state.

<hr>

### Methods
<hr>

#### createdAt

_?CarbonInterface_

A date object representing the date and time the state was created for the print job.

<hr>

### Static Methods
<hr>

#### all

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\PrintJobState>_

Retrieve all print job states.

```php
$states = PrintJobState::all();
```

| param | type | default |
| --- | --- |---------|
| `$params` | null\|array | null    |
| `$opts` | null\|array\|RequestOptions | null    |

<hr>
