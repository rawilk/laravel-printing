---
title: Print Tasks
sort: 3
---

## Introduction

A print task is used to send and print a document on the printer.

Print tasks can be sent to your printer by creating a new print task. At the bare minimum, you need your printer's id, and the content you are going to print.

```php
use Rawilk\Printing\Facades\Printing;

Printing::newPrintTask()
    ->printer($printerId)
    ->file('path_to_file.pdf')
    ->send();
```

## Options

There are several options you can set for a print job. You should consult with your print driver to see which options you have available to you.

```php
Printing::newPrintTask()
    ->printer($printerId)
    ->file('path_to_file.pdf')
    ->jobTitle('my job title')
    ->option('fit_to_page', true) // 'fit_to_page' is an available PrintNode option
    ->copies(2)
    ->tray('Tray 1') // check if your driver and printer supports this
    ->send();
```

Depending on the driver being used, there may be additional methods and even parameters to some of the standard methods shown above. Be sure to consult the documentation for your chosen driver to see what is available in the driver's print task api.

### Driver Options

-   See [PrintNode PrintTask](/docs/laravel-printing/{version}/printnode/print-task) for more options for the PrintNode driver.
-   More info on using CUPS options can be found here: [https://github.com/smalot/cups-ipp](https://github.com/smalot/cups-ipp)

## Conditionable

The base `PrintTask` class has been made `Conditionable`, so certain methods can be conditionally applied through `when`.

```php
use Rawilk\Printing\PrintTask;

Printing::newPrintTask()
    ->when(
        $someCondition === true,
        fn (PrintTask $task) => $task->content('...')
    )
```

## Reference

`Rawilk\Printing\PrintTask`

This is a general reference for the base `PrintTask` class/interface. Refer to the print task of your driver for a more complete reference.

### Methods
<hr>

#### content

_PrintTask_

Set the content to be printed.

| param | type |
| --- | --- |
| `$content` | string |

<hr>

#### file

_PrintTask_

Use the contents of a file to print. This should typically be a pdf file, however some drivers may support printing different file types.

| param | type |
| --- | --- |
| `$filePath` | string |

<hr>

#### url

_PrintTask_

Use the contents of a given url to print.

| param   | type |
|---------| --- |
| `$url`  | string |

#### jobTitle

_PrintTask_

Set's the name of the new print job. If a title is not specified, a random string will be used for the job title.

| param | type |
| --- | --- |
| `$jobTitle` | string |

<hr>

#### printer

_PrintTask_

Set the printer to send the new job to. This is a requirement for all drivers when creating new print jobs.

| param        | type                                            |
|--------------|-------------------------------------------------|
| `$printerId` | string\|int\|\Rawilk\Printing\Contracts\Printer |

<hr>

#### printSource

_PrintTask_

Sets the source of the print. This defaults to the application's name from `config('app.name')` and typically doesn't need to be set manually. Some drivers may even ignore this value, as it's not used in them.

| param | type |
| --- | --- |
| `$printSource` | string |

<hr>

#### tags

_PrintTask_

Specify tags for the new job. Not all drivers support this feature, so by default this method call does nothing. Refer to your driver of choice to see if this is available.

| param | type         |
| --- |--------------|
| `$tags` | array\|mixed |

<hr>

#### tray

_PrintTask_

Specify a tray to print to, if supported by the printer. Not all drivers may support this, so this method call does nothing by default. Refer to your driver of choice to see if this is available.

| param | type |
| --- | --- |
| `$tray` | string\|mixed |

<hr>

#### copies

_PrintTask_

Specify how many copies of the print job should be printed. Not all drivers may support this, so by default this method does nothing. Refer to your driver of choice to see if this is supported.

| param | type |
| --- | --- |
| `$copies` | int |

<hr>

#### option

_PrintTask_

Set an option for the print job. Options differ by print driver, so refer to your driver for the options that can be set.

| param | type |
| --- | --- |
| `$key` | string\|BackedEnum |
| `$value` | mixed |
