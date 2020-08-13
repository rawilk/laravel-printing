---
title: ReceiptPrinter
sort: 4
---

`Rawilk\Printing\Receipts\ReceiptPrinter`

## Methods

`ReceiptPrinter` is actually a wrapper around `Mike42\Escpos\Printer`. Most method calls are sent to that class if they are not found on `ReceiptPrinter`.
Some methods on this class have also been added to make interacting with it more convenient.

<x-table>
    <x-slot name="thead">
        <tr>
            <th>Method</th>
            <th>Params</th>
            <th>Description</th>
        </tr>
    </x-slot>

    <tr>
       <td><code>centerAlign</code></td>
        <td></td>
        <td>Center align any new text</td>
    </tr>
    <tr>
        <td><code>leftAlign</code></td>
        <td></td>
        <td>Left align any new text</td>
    </tr>
    <tr>
        <td><code>rightAlign</code></td>
        <td></td>
        <td>Right align any new text</td>
    </tr>
    <tr>
        <td><code>leftMargin</code></td>
        <td><code>int $margin = 0</code></td>
        <td>Set the left margin for any new text</td>
    </tr>
    <tr>
        <td><code>lineHeight</code></td>
        <td><code>int $height = null</code></td>
        <td>Set the line height for any new text</td>
    </tr>
    <tr>
        <td><code>text</code></td>
        <td>
            <code>string $text</code>
            <br><code>bool $insertNewLine = true</code>
        </td>
        <td>Write a line of text to the receipt. Set <code>$insertNewLine</code> to <code>true</code> to insert a new line character at the end of your string.</td>
    </tr>
    <tr>
        <td><code>twoColumnText</code></td>
        <td>
            <code>string $left</code>
            <br><code>string $right</code>
        </td>
        <td>Insert a line of text split into two columns, left and right justified. Useful for stuff like writing a line item and its price on the other side of the receipt</td>
    </tr>
    <tr>
        <td><code>barcode</code></td>
        <td>
            <code>$barcodeContent</code>
            <br><code>int $type = Printer::BARCODE_CODE39</code>
        </td>
        <td>Print a barcode to the receipt</td>
    </tr>
    <tr>
        <td><code>line</code></td>
        <td></td>
        <td>Print a line across the receipt using the <code>-</code> character</td>
    </tr>
    <tr>
        <td><code>doubleLine</code></td>
        <td></td>
        <td>Print a line across the receipt using the <code>=</code> character</td>
    </tr>
    <tr>
        <td><code>close</code></td>
        <td></td>
        <td>Close the connection to the receipt printer (This package uses a DummyConnection). This is called automatically for you.</td>
    </tr>
    <tr>
        <td><code>cut</code></td>
        <td>
            <code>int $mode = Printer::CUT_FULL</code>
            <br><code>int $lines = 3</code>
        </td>
        <td>Instruct the receipt printer to cut the paper</td>
    </tr>
    <tr>
        <td><code>feed</code></td>
        <td><code>int $lines = 1</code></td>
        <td>Feed an empty line(s) to the printer</td>
    </tr>
</x-table>

**Note:** Any methods not listed here can be found in the underlying Printer class.
