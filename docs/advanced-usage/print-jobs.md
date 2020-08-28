---
title: Print Jobs
sort: 3
---

If you need the details of a print job after it was created on your print server, can have access that from the return of `send()` on `PrintTask`.

```php
$printJob = Printing::newPrintTask()
    ->file('path/to/file.pdf')
    ->printer($printerId)
    ->send();

echo $printJob->id();
```

More info on the PrintJob can be found [in the api reference](/docs/laravel-printing/v1/api/print-job).
