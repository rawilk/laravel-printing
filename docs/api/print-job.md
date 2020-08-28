---
title: PrintJob
sort: 3
---

`Rawilk\Printing\Contracts\PrintJob`

### date
```php
/**
 * Returns the date the job was created.
 *
 * @return \DateTime|mixed
 */
public function date();
```

### id
```php
/**
 * Returns the id of the job.
 *
 * @return int|string
 */
public function id();
```

### name
```php
/**
 * Returns the id of name job.
 *
 * @return string|null
 */
public function name(): ?string;
```

### printerId
```php
/**
 * Returns the id the printer the job was sent to, if available.
 *
 * @return int|string|mixed
 */
public function printerId();
```

### printerName
```php
/**
 * Returns the name of the printer the job was sent to, if available.
 *
 * @return string|null
 */
public function printerName(): ?string;
```

### state
```php
/**
 * Returns the status of the job.
 *
 * @return string|null
 */
public function state(): ?string;
```
