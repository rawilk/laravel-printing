<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\PrinterCapabilities;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);

test('sets properties correctly', function () {
    $capabilities = new PrinterCapabilities(sampleData());

    $this->assertIsArray($capabilities->bins);
    $this->assertIsArray($capabilities->papers);
    $this->assertIsArray($capabilities->printRate);
    $this->assertFalse($capabilities->supportsCustomPaperSize);
    $this->assertCount(2, $capabilities->bins);
    $this->assertIsArray($capabilities->extent);
    $this->assertCount(2, $capabilities->extent);
    $this->assertEquals('ppm', $capabilities->printRate['unit']);
    $this->assertSame(23, $capabilities->printRate['rate']);
});

test('trays can be used as an alias to bins', function () {
    $capabilities = new PrinterCapabilities(sampleData());

    $expected = [
        'Automatically Select',
        'Tray 1',
    ];

    $this->assertCount(2, $capabilities->trays());
    $this->assertEquals($expected, $capabilities->bins);
    $this->assertEquals($expected, $capabilities->trays());
});

test('casts to array', function () {
    $capabilities = new PrinterCapabilities(sampleData());

    $asArray = $capabilities->toArray();

    foreach (sampleData() as $key => $value) {
        if ($key === 'printrate') {
            $key = 'printRate';
        } elseif ($key === 'supports_custom_paper_size') {
            $key = 'supportsCustomPaperSize';
        }

        $this->assertArrayHasKey($key, $asArray);
    }
});

// Helpers
function sampleData(): array
{
    return [
        'bins' => [
            'Automatically Select',
            'Tray 1',
        ],
        'collate' => false,
        'color' => true,
        'copies' => 1,
        'dpis' => [
            '600x600',
        ],
        'duplex' => false,
        'extent' => [
            [900, 900],
            [8636, 11176],
        ],
        'medias' => [],
        'nup' => [],
        'papers' => [
            'A4' => [
                2100,
                2970,
            ],
            'Letter' => [
                2159,
                2794,
            ],
            'Letter Small' => [
                2159,
                2794,
            ],
        ],
        'printrate' => [
            'unit' => 'ppm',
            'rate' => 23,
        ],
        'supports_custom_paper_size' => false,
    ];
}
