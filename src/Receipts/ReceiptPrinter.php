<?php

declare(strict_types=1);

namespace Rawilk\Printing\Receipts;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\Printer;

/**
 * @see Printer
 * @method self bitImage(\Mike42\Escpos\EscposImage $image, $size)
 * @method self close()
 * @method self cut(int $mode = Printer::CUT_FULL, int $lines = 3)
 * @method self feed(int $lines = 1)
 * @method self feedForm()
 * @method self feedReverse(int $lines = 1)
 * @method self graphics(\Mike42\Escpos\EscposImage $image, $size)
 * @method self pdf417Code(string $content, int $width = 3, int $heightMultiplier = 3, int $dataColumnCount = 0, float $ec = 0.10, int $options = Printer::PDF417_STANDARD)
 * @method self pulse(int $pin = 0, int $on_ms = 120, int $off_ms = 240)
 * @method self qrCode(string $content, int $ec = Printer::QR_ECLEVEL_L, int $size = 3, int $model = Printer::QR_MODEL_2)
 * @method self selectPrintMode(int $mode = Printer::MODE_FONT_A)
 * @method self setBarcodeHeight(int $height = 8)
 * @method self setBarcodeWidth(int $width = 3)
 * @method self setColor(int $color = Printer::COLOR_1)
 * @method self setDoubleStrike(bool $on = true)
 * @method self setEmphasis(bool $on = true)
 * @method self setFont(int $font = Printer::FONT_A)
 * @method self setJustification(int $justification = Printer::JUSTIFY_LEFT)
 * @method self setLineSpacing(int $height = null)
 * @method self setPrintLeftMargin(int $margin = 0)
 * @method self setPrintWidth(int $width = 512)
 * @method self setReverseColors(bool $on = true)
 * @method self setTextSize(int $widthMultiplier, int $heightMultiplier)
 * @method self setUnderline(int $underline = Printer::UNDERLINE_SINGLE)
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

        if ($remaining <= 0) {
            $remaining = 1;
        }

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
            $this->printer->{$name}(...$arguments);

            return $this;
        }

        throw new InvalidArgumentException("Method [{$name}] not found on receipt printer object.");
    }

    public function __destruct()
    {
        $this->close();
    }
}
