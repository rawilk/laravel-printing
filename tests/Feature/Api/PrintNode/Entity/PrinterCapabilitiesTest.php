<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\PrinterCapabilities;

test('sets properties correctly', function () {
    $capabilities = new PrinterCapabilities(sampleCapabilitiesData());

    expect($capabilities->bins)->toBeArray();
    expect($capabilities->papers)->toBeArray();
    expect($capabilities->printRate)->toBeArray();
    expect($capabilities->supportsCustomPaperSize)->toBeFalse();
    expect($capabilities->bins)->toHaveCount(2);
    expect($capabilities->extent)->toBeArray();
    expect($capabilities->extent)->toHaveCount(2);
    expect($capabilities->printRate['unit'])->toEqual('ppm');
    expect($capabilities->printRate['rate'])->toBe(23);
});

test('trays can be used as an alias to bins', function () {
    $capabilities = new PrinterCapabilities(sampleCapabilitiesData());

    $expected = [
        'Automatically Select',
        'Tray 1',
    ];

    expect($capabilities->trays())->toHaveCount(2);
    expect($capabilities->bins)->toEqual($expected);
    expect($capabilities->trays())->toEqual($expected);
});

test('casts to array', function () {
    $capabilities = new PrinterCapabilities(sampleCapabilitiesData());

    $asArray = $capabilities->toArray();

    foreach (sampleCapabilitiesData() as $key => $value) {
        if ($key === 'printrate') {
            $key = 'printRate';
        } elseif ($key === 'supports_custom_paper_size') {
            $key = 'supportsCustomPaperSize';
        }

        $this->assertArrayHasKey($key, $asArray);
    }
});

// Helpers
function sampleCapabilitiesData(): array
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
