---
title: Receipt Printing
sort: 1
---

If you have a receipt printer, you can easily print receipts to it via the `Rawilk\Printing\Receipts\ReceiptPrinter`. This will generate a string
that you can then send to your receipt printer.

```php
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

More info on the receipt printer can be found in [the api reference](/docs/laravel-printing/v1/api/receipt-printer).
