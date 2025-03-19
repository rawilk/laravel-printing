---
title: Raw Content Printing
sort: 2
---

## Introduction

Depending on your print driver and printer, you can send raw text or content from an url to be printed instead of using
a pdf file.

## Content

Send a string of text to be printed using the `content()` method on PrintTask. This is the method you should be using if you are printing
a receipt.

Some drivers also may require you to set a content type as well. Be sure to refer to the specific driver's api when setting the content.

```php
Printing::newPrintTask()
    ->printer($printerId)
    ->content('hello world')
    ->send();
```

## URL

You can also print the contents of a URL using the `url()` method on PrintTask.

```php
Printing::newPrintTask()
    ->printer($printerId)
    ->url('https://google.com')
    ->send();
```
