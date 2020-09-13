---
title: Multiple Drivers
sort: 5
---

## Introduction

If you have multiple print drivers you need to print with, you can easily do so by calling
`driver('driver_name')` on the `Printing` facade. This could be useful if you print receipts
through PrintNode and then regular documents through CUPS or some other custom driver you have
installed. 

## Switching on the fly

By default, the package only supports using one driver at a time, but you can switch to using a different driver
at runtime if you need to. Let's say you need to print most documents using PrintNode, but for one specific document,
you need to use CUPS. You can do so like this:

```php
// Send a job to printnode
Printing::newPrintTask()
    ->printer($printerId)
    ->file('file_path.pdf')
    ->send();

// Send a job to the cups server
Printing::driver('cups')
    ->newPrintTask()
    ->printer($cupsPrinterId)
    ->file('file_path.pdf')
    ->send();
```
