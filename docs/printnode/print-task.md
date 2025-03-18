---
title: PrintTask
sort: 3
---

## Introduction

`Rawilk\Printing\Drivers\PrintNode\PrintTask`

The `PrintTask` provided by the `PrintNode` driver offers some additional functionality to the base PrintTask class, as detailed below.

Refer to [PrintTask](/docs/laravel-printing/{version}/basic-usage/print-tasks) for anything not detailed here.

## Reference

### Methods
<hr>

#### content

_PrintTask_

Sets the content to be printed. You may also specify the content type through here as well. With PrintNode, you may either print raw content or pdf content. The driver will automatically base64_encode the content for you.

| param | type                                                    | default                |
| --- |---------------------------------------------------------|------------------------|
| `$content` | string                                                  |                        |
| `$contentType` | string\Rawilk\Printing\Api\PrintNode\Enums\ContentType  | `ContentType::RawBase64` |

<hr>

#### file

_PrintTask_

Specify a file path to fetch the contents from to print. With PrintNode, the file must be a PDF file. The driver will handle encoding the pdf content with base64_encode automatically. 

| param | type |
| --- | --- |
| `$filePath` | string |

<hr>

#### url

_PrintTask_

Specify an url for PrintNode to fetch content from to print. PrintNode typically expects an url to a pdf document, however raw html content can also be printed by setting the `$raw` argument to `true`.

| param | type | default |
| --- | --- | --- |
| `$url` | string | |
| `$raw` | bool | `false` |

See [withAuth](#user-content-withAuth) if your url requires authentication to access it.

<hr>

#### option

_PrintTask_

Set an option for the print job. Please refer to [PrintJob Options](https://www.printnode.com/en/docs/api/curl#printjob-options) for a reference to all options supported by PrintNode.

You may also refer to and use the [PrintJobOption](https://github.com/rawilk/laravel-printing/blob/main/src/Api/PrintNode/Enums/PrintJobOption.php) enum for setting options on a print job.

| param    | type                   |
|----------|------------------------|
| `$key`   | string\|PrintJobOption |
| `$value` | mixed                  |

<hr>

#### range

_PrintTask_

Specify a range of pages to print from a PDF. 

| param | type              | default | 
| --- |-------------------|---------|
| `$start` | string\|int       |
| `$end` | string\|int\|null | null    |

Examples:

- To print pages 1 through 3: `->range(1, 3)`
- To print pages 1 and 3: `->range('1,3')`
- To print pages 1 through 5 inclusive: `->range('-5')`
- To print all pages except page 2: `->range('1,3', '-')`

<hr>

#### tray

_PrintTask_

Print to a specific tray on a printer if the printer supports it.

| param | type |
| --- | --- |
| `$tray` | string |

<hr>

#### copies

_PrintTask_

The number of copies to print. Defaults to `1`. Maximum value is as reported by the printer capabilities property `copies` on the printer.

| param | type |
| --- | --- |
| `$copies` | int |

<hr>

#### contentType

_PrintTask_

Specify the content type for the print job.

| param | type |
| --- | --- |
| `$contentType` | string\|Rawilk\Printing\Api\PrintNode\Enums\ContentType |

<hr>

#### fitToPage

_PrintTask_

Indicates the printer should automatically fit the document to the page.

| param | type |
| --- | --- |
| `$condition` | bool |

<hr>

#### paper

_PrintTask_

Specify the name of the paper size to print on. This must be one of the keys in the object returned by the printer capability property `papers`.

| param | type |
| --- | --- |
| `$paper` | string |

<hr>

#### expireAfter

_PrintTask_

The maximum number of seconds PrintNode should retain the print job in the event the print job cannot be printed immediately. The current default is 14 days, or 1,209,600 seconds.

The value provided to this method should be in seconds.

| param | type |
| --- | --- |
| `$expireAfter` | int |

<hr>

#### printQty

_PrintTask_

A positive integer specifying the number of times this print job should be delivered to the print queue. This differs from the `copies` option in that this will send the document to the printer multiple times and does not rely on printer driver support.

This is the only way to produce multiple copies when RAW printing.

This value defaults to `1`.

| param | type |
| --- | --- |
| `$qty` | int |

<hr>

#### withAuth

_PrintTask_

When sending an url to PrintNode to print, and that url requires authentication to access it, this method should be used. 

This supports both HTTP basic and Digest Authentication where you can specify a username and password.

| param | type                                                           | default                     | 
| --- |----------------------------------------------------------------|-----------------------------|
| `$username` | string                                                         |                             |
| `$password` | string                                                         |                             |
| `$authenticationType` | string\|Rawilk\Printing\Api\PrintNode\Enums\AuthenticationType | `AuthenticationType::Basic` |

<hr>

#### send

_Rawilk\Printing\Drivers\PrintNode\Entity\PrintJob_

Create and send the print job to your printer. The driver will return an object representing the print job.

You may also specify a specific api key to use and/or additional headers to send through with the request with the `$opts` argument.

| param | type                                                           | default | 
| --- |----------------------------------------------------------------|---------|
| `$opts` | null\|array\|Rawilk\Printing\Api\PrintNode\Util\RequestOptions | null    |

The most common use case for this argument is setting an api key to use for a single request. You can do so like this:

```php
Printing::newPrintTask()
    ->printer($printerId)
    ->content('Hello world')
    ->send([
        'api_key' => 'my-key',
    ]);
```

PrintNode also supports setting an [Idempotency Key Header](https://www.printnode.com/en/docs/api/curl#idempotency) with this request. This ensures PrintNode will only print a print job once, even if you submit a job multiple times to the API.

The driver will automatically set this header for you, however you may wish to specify your own key for this. You may do so like this:

```php
Printing::newPrintTask()
    ->printer($printerId)
    ->content('Hello world')
    ->send([
        'idempotency_key' => 'foo',
    ]);
```
