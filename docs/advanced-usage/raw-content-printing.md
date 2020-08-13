---
title: Raw Content Printing
sort: 2
---

Depending on your print driver and printer, you can send raw text or content from a url to be printed instead of using
a pdf file.

## Content

Send a string of text to be printed using the `content()` method on PrintTask. This is the method you should be using if you are printing
a receipt.

<x-code lang="php">
Printing::newPrintTask()
    ->printer($printerId)
    ->content('hello world')
    ->send();
</x-code>

## URL

You can also print the contents of a URL using the `url()` method on PrintTask.

<x-code lang="php">
Printing::newPrintTask()
    ->printer($printerId)
    ->url('https://google.com')
    ->send();
</x-code>
