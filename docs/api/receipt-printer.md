---
title: ReceiptPrinter
sort: 4
---

`Rawilk\Printing\Receipts\ReceiptPrinter`

`ReceiptPrinter` is actually a wrapper around `Mike42\Escpos\Printer`. Most method calls are sent to that class if they are not found on `ReceiptPrinter`.
Some methods on this class have also been added to make interacting with it more convenient.

### centerAlign
```php
/**
 * Center align any new text.
 *
 * @return ReceiptPrinter
 */
public function centerAlign(): self;
```

### leftAlign
```php
/**
 * Left align any new text
 *
 * @return ReceiptPrinter
 */
public function leftAlign(): self;
```

### rightAlign
```php
/**
 * Right align any new text.
 *
 * @return ReceiptPrinter
 */
public function rightAlign(): self;
```

### leftMargin
```php
/**
 * Set the left margin for any new text. 
 *
 * @param int $margin
 * @return ReceiptPrinter
 */
public function leftMargin(int $margin = 0): self;
```

### lineHeight
```php
/**
 * Set the line height for any new text.
 *
 * @param int|null $height
 * @return ReceiptPrinter
 */
public function lineHeight(int $height = null): self;
```

### text
```php
/**
 * Write a line of text to the receipt.
 *
 * @param string $text
 * @param bool $insertNewLine Set to true to insert a new line character at the end of your string.
 * @return ReceiptPrinter
 */
public function text(string $text, bool $insertNewLine = true): self;
```

### twoColumnText
```php
/**
 * Insert a line of text split into two columns, left and right justified.
 * Useful for stuff like writing a line item and its price on a line.
 *
 * @param string $left
 * @param string $right
 * @return ReceiptPrinter
 */
public function twoColumnText(string $left, string $right): self;
```

### barcode
```php
/**
 * Print a barcode to the receipt.
 *
 * @param string|mixed $barcodeContent
 * @param int $type
 * @return ReceiptPrinter
 */
public function barcode($barcodeContent, int $type = \Mike42\Escpos\Printer::BARCODE_CODE39): self;
```

### line
```php
/**
 * Print a line across the receipt using the "-" character.
 *
 * @return ReceiptPrinter
 */
public function line(): self;
```

### doubleLine
```php
/**
 * Print a line across the receipt using the "=" character. 
 *
 * @return ReceiptPrinter
 */
public function doubleLine(): self;
```

### close
```php
/**
 * Close the connection to the receipt printer (this package used a DummyConnection).
 * This is automatically called for you.
 *
 * @return ReceiptPrinter
 */
public function close(): self;
```

### cut
```php
/**
 * Instruct the receipt printer to cut the paper.
 * Can be called multiple times.
 *
 * @param int $mode
 * @param int $lines
 * @return ReceiptPrinter
 */
public function cut(int $mode = \Mike42\Escpos\Printer::CUT_FULL, int $lines = 3): self;
```

### feed
```php
/**
 * Feed an empty line(s) to the receipt printer.
 *
 * @param int $lines = 1
 * @return ReceiptPrinter
 */
public function feed(int $lines = 1): self;
```

{.tip}
> **Note:** Any methods not listed here can be found in the underlying Printer class.
