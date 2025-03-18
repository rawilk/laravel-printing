---
title: Receipt Printing
sort: 1
---

## Introduction

If you have a receipt printer, you can easily print receipts to it via the `Rawilk\Printing\Receipts\ReceiptPrinter`. This will generate a string
that you can then send to your receipt printer.

```php
use Rawilk\Printing\Receipts\ReceiptPrinter;

// First generate the receipt
$receipt = (string) (new ReceiptPrinter)
    ->centerAlign()
    ->text('My heading')
    ->leftAlign()
    ->line()
    ->twoColumnText('Item 1', '2.00')
    ->twoColumnText('Item 2', '4.00')
    ->feed(2)
    ->centerAlign()
    ->barcode('1234')
    ->cut();

// Now send the string to your receipt printer
Printing::newPrintTask()
    ->printer($receiptPrinterId)
    ->content($text)
    ->send();
```

If you are using the PrintNode driver, the content will be `base64_encoded` automatically for you.

## Conditionable

Like many classes in this package, the `ReceiptPrinter` is `Conditionable`, so you may chain on conditions using `when`.

```php
$receipt = (string) (new ReceiptPrinter)
    ->text('foo')
    ->when(
        $someCondition === true,
        fn (ReceiptPrinter $printer) => $printer->centerAlign()
    );
```

## Reference

The package's ReceiptPrinter implementation is actually a wrapper around the `Mike42\Escpos\Printer` class. Most method calls are forwarded to that class if they are not found on the `ReceiptPrinter`. Some methods have also been added to make interacting with it more convenient.

### Methods
<hr>

#### centerAlign

Center align any new text.

<hr>

#### leftAlign

Left align any new text.

<hr>

#### rightAlign

Right align any new text.

<hr>

#### leftMargin

Set the left margin for any new text. The unit for the margin will be `dots`.

| param | type | default |
| --- | --- | --- |
| `$margin` | int | 0 |

<hr>

#### lineHeight

Set the line height for any new text. The unit for the line height will be `dots`. Use `null` or omit the `$height` parameter to reset the line height to the printer's defaults for any new text.

| param | type | default |
| --- | --- | --- |
| `$height` | int|null | null |

<hr>

#### text

Write a line of text to the receipt.

| param | type | default | description                                                            |
| --- | --- | --- |------------------------------------------------------------------------|
| `$text` | string | | the text to print                                                      |
| `$insertNewLine` | bool | true | Set to `true` to insert a new line character at the end of your string |

<hr>

#### twoColumnText

Insert a line of text split into two columns, left and right justified. Useful for stuff like writing a line item and its price on a line.

| param | type |
| --- | --- |
| `$left` | string |
| `$right` | string | 

<hr>

#### barcode

Print a barcode to the receipt.

| param | type | default |
| --- | --- |---------|
| `$barcodeContent` | string |         |
| `$type` | int | `Mike42\Escpos\Printer::BARCODE_CODE39`        |

<hr>

#### line

Print a line across the receipt using the `-` character.

<hr>

#### doubleLine

Print a line across the receipt using the `=` character.

<hr>

#### close

Close the connection to the receipt printer (this package uses a `DummyConnection`). This is automatically called for you.

<hr>

#### cut

Instruct the receipt printer to cut the paper; can be called multiple times.

| param | type | default |
| --- | --- | --- |
| `$mode` | int | `Mike42\Escpos\Printer::CUT_FULL` |
| `$lines` | int | 3 |

#### lines

Feed an empty line(s) to the receipt printer.

| param | type | default |
| --- | --- | --- | 
| `$lines` | int | 1 |

<hr>

> {tip} Any methods not listed here can be found in the underlying `Mike42\Escpos\Printer` class.
