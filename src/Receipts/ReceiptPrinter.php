<?php

namespace Rawilk\Printing\Receipts;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\Printer;

/**
 * @method self close()
 * @method self setBarcodeHeight(int $height = 8)
 * @method self setBarcodeWidth(int $width = 3)
 * @method self setJustification(int $justification = Printer::JUSTIFY_LEFT)
 * @method self setLineSpacing(int $height = null)
 * @method self setPrintLeftMargin(int $margin = 0)
 */
class ReceiptPrinter
{
    protected DummyPrintConnector $connector;
    protected Printer $printer;
    protected static int $lineCharacterLength;

    public function __construct()
    {
        $this->connector = new DummyPrintConnector;
        $this->printer = new Printer($this->connector);

        static::$lineCharacterLength = config('printing.receipts.line_character_length', 45);
    }

    public function centerAlign(): self
    {
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);

        return $this;
    }

    public function leftAlign(): self
    {
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);

        return $this;
    }

    public function rightAlign(): self
    {
        $this->printer->setJustification(Printer::JUSTIFY_RIGHT);

        return $this;
    }

    public function leftMargin(int $margin = 0): self
    {
        $this->printer->setPrintLeftMargin($margin);

        return $this;
    }

    public function lineHeight(int $height = null): self
    {
        $this->printer->setLineSpacing($height);

        return $this;
    }

    public function text(string $text, bool $insertNewLine = true): self
    {
        if ($insertNewLine && ! Str::endsWith($text, "\n")) {
            $text = "{$text}\n";
        }

        $this->printer->text($text);

        return $this;
    }

    public function twoColumnText(string $left, string $right): self
    {
        $remaining = static::$lineCharacterLength - strlen($left) - strlen($right);

        return $this->text($left . str_repeat(' ', $remaining) . $right);
    }

    public function barcode($barcodeContent, int $type = Printer::BARCODE_CODE39): self
    {
        $this->printer->setBarcodeWidth(config('printing.receipts.barcode_width', 2));
        $this->printer->setBarcodeHeight(config('printing.receipts.barcode_height', 64));
        $this->printer->barcode($barcodeContent, $type);

        return $this;
    }

    public function line(): self
    {
        return $this->text(str_repeat('-', static::$lineCharacterLength));
    }

    public function doubleLine(): self
    {
        return $this->text(str_repeat('=', static::$lineCharacterLength));
    }

    public function __toString(): string
    {
        return $this->connector->getData();
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->printer, $name)) {
            $this->printer->{$name}($arguments);

            return $this;
        }

        throw new InvalidArgumentException("Method [{$name}] not found on receipt printer object.");
    }

    public function __destruct()
    {
        $this->close();
    }
}
