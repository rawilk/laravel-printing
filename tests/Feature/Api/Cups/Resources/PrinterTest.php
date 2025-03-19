<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\PrinterState;
use Rawilk\Printing\Api\Cups\Resources\Printer;

it('creates from response data', function () {
    $printer = Printer::make(baseCupsPrinterData());

    expect($printer)
        ->uri->toBe('localhost:631')
        ->printerUriSupported->toBe('localhost:631')
        ->printerName->toBe('TestPrinter')
        ->printerState->toBe(PrinterState::Idle->value);
});
