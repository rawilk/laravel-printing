---
title: Print Tasks
sort: 3
---

## Introduction

Print tasks can be sent to your printer by creating a new print task. At the bare minimum, you need your printer's id, and the content you are going to print.

```php
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

**Note:** If using CUPS, you can pass in a `$contentType` as a second parameter to the `file()`, `url()`, and
`content()` methods. The default is `application/octet-stream` (PDF). More types can be found in
`Rawilk\Printing\Drivers\Cups\ContentType.php`.

### Driver Options

- More PrintNode options can be found here: [https://www.printnode.com/en/docs/api/curl#printjob-options](https://www.printnode.com/en/docs/api/curl#printjob-options)
- More info on using CUPS options can be found here: [https://github.com/smalot/cups-ipp](https://github.com/smalot/cups-ipp)

More info on print tasks can be found [in the api reference](/laravel-printing/v2/api/print-task).
