---
title: PrintTask
sort: 3
---

## Introduction

`Rawilk\Printing\Drivers\Cups\PrintTask`

The `PrintTask` provided by the `CUPS` driver offers some additional functionality to the base PrintTask class, as detailed below.

Refer to [PrintTask](/docs/laravel-printing/{version}/basic-usage/print-tasks) for anything not detailed here.

## Reference

### Methods
<hr>

#### content

_PrintTask_

Sets the content to be printed. You may also specify the content type through here as well.

| param | type                                               | default          |
| --- |----------------------------------------------------|------------------|
| `$content` | string                                             |                  |
| `$contentType` | string\|Rawilk\Printing\Api\Cups\Enums\ContentType | ContentType::Pdf |

<hr>

#### file

_PrintTask_

Specify a file path to fetch the contents from to print.

| param          | type                                               | default          |
|----------------|----------------------------------------------------|------------------|
| `$filePath`    | string                                             |                  |
| `$contentType` | string\|Rawilk\Printing\Api\Cups\Enums\ContentType | ContentType::Pdf |

<hr>

#### option

_PrintTask_

Set an option for the new print job. Options sent to CUPS must be in a specific format, which can be achieved easily by using the `OperationAttribute` enum from the CUPS api. Please submit a PR or raise an issue if there is an attribute you need that is not provided by the enum.

| param | type |
| --- | --- |
| `$key` | string\|OperationAttribute |
| `$value` | mixed |

Example:

```php
use Rawilk\Printing\Api\Cups\Enums\OperationAttribute;
use Rawilk\Printing\Facades\Printing;

Printing::newPrintTask()
    ->option(
        OperationAttribute::Copies,
        OperationAttribute::Copies->toType(2),
    );
```

In the example above, we're instructing CUPS to print two copies of the content being sent to the printer.

<hr>

#### contentType

_PrintTask_

Sets the content type of the content being printed.

| param | type | 
| --- | --- |
| `$contentType` | string\|Rawilk\Printing\Api\Cups\Enums\ContentType |

<hr>

#### orientation

_PrintTask_

Sets the page orientation of the paper.

| param | type |
| --- | --- |
| `$value` | string\|Rawilk\Printing\Api\Cups\Enums\Orientation |

<hr>

#### user

_PrintTask_

Set the name of the user printing the document.

| param | type |
| --- | --- |
| `$name` | string |

<hr>

#### send

_Rawilk\Printing\Drivers\Cups\Entity\PrintJob_

Create and send the print job to your printer. The driver will return an object representing the print job.

You may also specify credentials for a CUPS server per-request through the `$opts` argument.

```php
use Rawilk\Printing\Api\Cups\Enums\ContentType;

Printing::newPrintTask()
    ->printer($printerId)
    ->content('hello world', ContentType::Plain)
    ->send([
        'ip' => '127.0.0.1',
        'username' => 'foo',
        'password' => 'bar',
        'port' => 631,
        'secure' => true,
    ]);
```

> {tip} You only need to specify the configuration values you need here. Everything else will attempt to resolve from your CUPS configuration.

<hr>
