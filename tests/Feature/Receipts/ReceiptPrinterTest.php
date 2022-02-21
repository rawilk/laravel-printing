<?php

declare(strict_types=1);

use Mike42\Escpos\Printer;
use Rawilk\Printing\Receipts\ReceiptPrinter;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    config([
        'printing.receipts.line_character_length' => 45,
        'printing.receipts.print_width' => 550,
    ]);
});

it('prints text', function () {
    $text = (string) (new ReceiptPrinter)->text('Hello world');

    expect($text)->toEqual(expectedText("Hello world\n"));

    $text = (string) (new ReceiptPrinter)->text('Hello world', false);

    expect($text)->toEqual(expectedText('Hello world'));
});

it('can print text in two columns justified on each side', function () {
    $text = (string) (new ReceiptPrinter)->twoColumnText('Hello', 'world');
    $expected = expectedText("Hello                                   world\n");

    expect($text)->toEqual($expected);
});

it('prints a single dashed line', function () {
    $text = (string) (new ReceiptPrinter)->line();
    $expected = expectedText(str_repeat('-', 45) . "\n");

    expect($text)->toEqual($expected);

    config([
        'printing.receipts.line_character_length' => 20,
    ]);

    $text = (string) (new ReceiptPrinter)->line();
    $expected = expectedText(str_repeat('-', 20) . "\n");

    expect($text)->toEqual($expected);
});

it('prints a dashed double line', function () {
    $text = (string) (new ReceiptPrinter)->doubleLine();
    $expected = expectedText(str_repeat('=', 45) . "\n");

    expect($text)->toEqual($expected);

    config([
        'printing.receipts.line_character_length' => 20,
    ]);

    $text = (string) (new ReceiptPrinter)->doubleLine();
    $expected = expectedText(str_repeat('=', 20) . "\n");

    expect($text)->toEqual($expected);
});

it('prints a barcode', function () {
    $text = (string) (new ReceiptPrinter)->barcode('1234');
    $expected = expectedText("\x1Dw\x02\x1Dh@\x1DkE\x041234");

    expect($text)->toEqual($expected);
});

it('sets the line height', function () {
    $text = (string) (new ReceiptPrinter)->lineHeight(4);
    $expected = expectedText("\e3\x04");

    expect($text)->toEqual($expected);
});

it('sets the left margin', function () {
    $text = (string) (new ReceiptPrinter)->leftMargin(40);
    $expected = expectedText("\x1DL(\x00");

    expect($text)->toEqual($expected);
});

/**
 * @param string $alignment
 * @param string $expected
 */
it('aligns text', function (string $alignment, string $expected) {
    $text = (string) (new ReceiptPrinter)->{"{$alignment}Align"}();

    expect($text)->toEqual(expectedText($expected));
})->with('textAlignments');

it('forwards method calls to the printer object', function () {
    $text = (string) (new ReceiptPrinter)->cut();
    $expected = expectedText("\x1DVA\x03");

    expect($text)->toEqual($expected);

    $text = (string) (new ReceiptPrinter)->cut(Printer::CUT_FULL, 6);
    $expected = expectedText("\x1DVA\x06");

    expect($text)->toEqual($expected);
});

// Datasets
dataset('textAlignments', [
    ['left', "\ea\x00"],
    ['right', "\ea\x02"],
    ['center', "\ea\x01"],
]);

// Helpers
function expectedText(string $expected): string
{
    return static::$startCharacter . $expected;
}
