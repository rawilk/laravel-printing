---
title: Printer
sort: 1
---

`Rawilk\Printing\Contracts\Printer`

### id
```php
/**
 * Returns the printer's id.
 *
 * @return int|string
 */
public function id();
```

### name
```php
/**
 * Returns the printer's name.
 *
 * @return string|null
 */
public function name(): ?string;
```

### description
```php
/**
 * Returns the printer's description.
 *
 * @return string|null
 */
public function description(): ?string;
```

### capabilities
```php
/**
 * Returns the printer's capabilities.
 *
 * @return array
 */
public function capabilities(): array;
```

### trays
```php
/**
 * Returns the printer's available trays.
 *
 * @return array
 */
public function trays(): array;
```

### status
```php
/**
 * Returns the printer's current status.
 *
 * @return string
 */
public function status(): string;
```

### isOnline
```php
/**
 * Determine if the printer is currently "online".
 *
 * @return bool
 */
public function isOnline(): bool;
```

### jobs
```php
/**
 * Returns the jobs for a printer.
 *
 * @return \Illuminate\Support\Collection
 */
public function jobs(): Collection;
```
**Note:** This feature is not yet implemented for the PrintNode driver.

### toArray
```php
/**
 * Returns an array representation of the printer.
 * This method is also called if casting the printer to an array ((array) $printer)
 *
 * @return array
 */
public function toArray(): array;
```
