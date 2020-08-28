---
title: PrintTask
sort: 2
---

`Rawilk\Printing\PrintTask`

### content
```php
/**
 * Set the content to be printed. 
 *
 * @param string $content
 * @return PrintTask 
 */
public function content($content): self;
```

### file
```php
/**
 * Set the path to a pdf file to be printed. 
 *
 * @param string $filePath
 * @return PrintTask
 */
public function file(string $filePath): self;
```

### url
```php
/**
 * Set a url to be printed.
 *
 * @param string $url
 * @return PrintTask
 */
public function url(string $url): self;
```

### jobTitle
```php
/**
 * Set the title of the print task.
 * Defaults to a randomly generated id.
 *
 * @param string $jobTitle
 * @return PrintTask
 */
public function jobTitle(string $jobTitle): self;
```

### printer
```php
/**
 * Set the id of the printer to print to. This method must be called
 * when printing. 
 *
 * @param string|int $printerId
 * @return PrintTask
 */
public function printer($printerId): self;
```

### printSource
```php
/**
 * Set a source of the print task. Defaults to the application name.
 *
 * @param string $printSource
 * @return PrintTask
 */
public function printSource(string $printSource): self;
```

### tags
```php
/**
 * Add tags to the task if your driver supports it. 
 *
 * @param string|array|mixed $tags
 * @return PrintTask 
 */
public function tags($tags): self;
```

### tray
```php
/**
 * Set a tray to print to if your printer and driver support it.
 *
 * @param string $tray
 * @return PrintTask
 */
public function tray($tray): self;
```

### copies
```php
/**
 * Set the amount of copies to print.
 *
 * @param int $copies
 * @return PrintTask
 */
public function copies(int $copies): self;
```

### range
```php
/**
 * Set the page range to print.
 * Omit $end to start at a page and continue to the end. 
 *
 * @param int|string $start
 * @param int|null @end
 * @return PrintTask
 */
public function range($start, $end = null): self;
```

### option
```php
/**
 * Set an option for the print task that your driver supports.
 *
 * @param string $key
 * @param mixed $value
 * @return PrintTask
 */
public function option(string $key, $value): self;
```

### send
```php
/**
 * Send the print task to your print server. 
 * If successful, it will return a PrintJob instance.
 *
 * @return PrintJob 
 */
public function send(): PrintJob;
```
