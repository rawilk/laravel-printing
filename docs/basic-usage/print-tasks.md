---
title: Print Tasks
sort: 3
---

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

### Driver Options

- More PrintNode options can be found here: [https://www.printnode.com/en/docs/api/curl#printjob-options](https://www.printnode.com/en/docs/api/curl#printjob-options)

More info on print tasks can be found [in the api reference](/laravel-printing/v1/api/print-task).
