<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Receipts;

use Mike42\Escpos\Printer;
use Rawilk\Printing\Receipts\ReceiptPrinter;
use Rawilk\Printing\Tests\TestCase;

class ReceiptPrinterTest extends TestCase
{
    protected static string $startCharacter = "\e@";

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'printing.receipts.line_character_length' => 45,
            'printing.receipts.print_width' => 550,
        ]);
    }

    /** @test */
    public function it_prints_text(): void
    {
        $text = (string) (new ReceiptPrinter)->text('Hello world');

        $this->assertEquals($this->expectedText("Hello world\n"), $text);

        $text = (string) (new ReceiptPrinter)->text('Hello world', false);

        $this->assertEquals($this->expectedText('Hello world'), $text);
    }

    /** @test */
    public function it_can_print_text_in_two_columns_justified_on_each_side(): void
    {
        $text = (string) (new ReceiptPrinter)->twoColumnText('Hello', 'world');
        $expected = $this->expectedText("Hello                                   world\n");

        $this->assertEquals($expected, $text);
    }

    /** @test */
    public function it_prints_a_single_dashed_line(): void
    {
        $text = (string) (new ReceiptPrinter)->line();
        $expected = $this->expectedText(str_repeat('-', 45) . "\n");

        $this->assertEquals($expected, $text);

        config([
            'printing.receipts.line_character_length' => 20,
        ]);

        $text = (string) (new ReceiptPrinter)->line();
        $expected = $this->expectedText(str_repeat('-', 20) . "\n");

        $this->assertEquals($expected, $text);
    }

    /** @test */
    public function it_prints_a_dashed_double_line(): void
    {
        $text = (string) (new ReceiptPrinter)->doubleLine();
        $expected = $this->expectedText(str_repeat('=', 45) . "\n");

        $this->assertEquals($expected, $text);

        config([
            'printing.receipts.line_character_length' => 20,
        ]);

        $text = (string) (new ReceiptPrinter)->doubleLine();
        $expected = $this->expectedText(str_repeat('=', 20) . "\n");

        $this->assertEquals($expected, $text);
    }

    /** @test */
    public function it_prints_a_barcode(): void
    {
        $text = (string) (new ReceiptPrinter)->barcode('1234');
        $expected = $this->expectedText("\x1Dw\x02\x1Dh@\x1DkE\x041234");

        $this->assertEquals($expected, $text);
    }

    /** @test */
    public function it_sets_the_line_height(): void
    {
        $text = (string) (new ReceiptPrinter)->lineHeight(4);
        $expected = $this->expectedText("\e3\x04");

        $this->assertEquals($expected, $text);
    }

    /** @test */
    public function it_sets_the_left_margin(): void
    {
        $text = (string) (new ReceiptPrinter)->leftMargin(40);
        $expected = $this->expectedText("\x1DL(\x00");

        $this->assertEquals($expected, $text);
    }

    /**
     * @test
     * @dataProvider textAlignments
     * @param string $alignment
     * @param string $expected
     */
    public function it_aligns_text(string $alignment, string $expected): void
    {
        $text = (string) (new ReceiptPrinter)->{"{$alignment}Align"}();

        $this->assertEquals($this->expectedText($expected), $text);
    }

    /** @test */
    public function it_forwards_method_calls_to_the_printer_object(): void
    {
        $text = (string) (new ReceiptPrinter)->cut();
        $expected = $this->expectedText("\x1DVA\x03");

        $this->assertEquals($expected, $text);

        $text = (string) (new ReceiptPrinter)->cut(Printer::CUT_FULL, 6);
        $expected = $this->expectedText("\x1DVA\x06");

        $this->assertEquals($expected, $text);
    }

    public function textAlignments(): array
    {
        return [
            ['left', "\ea\x00"],
            ['right', "\ea\x02"],
            ['center', "\ea\x01"],
        ];
    }

    protected function expectedText(string $expected): string
    {
        return static::$startCharacter . $expected;
    }
}
