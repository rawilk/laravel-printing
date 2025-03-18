<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Resources\Support\PrinterCapabilities;
use Rawilk\Printing\Api\PrintNode\Resources\Support\PrintRate;

test('capabilities data', function () {
    $obj = PrinterCapabilities::make($data = sampleCapabilitiesData());

    expect($obj)
        ->bins->toEqualCanonicalizing($data['bins'])
        ->collate->toBeFalse()
        ->color->toBeTrue()
        ->copies->toBe(1)
        ->dpis->toEqualCanonicalizing($data['dpis'])
        ->duplex->toBeFalse()
        ->papers->toEqualCanonicalizing($data['papers'])
        ->printrate->toBeInstanceOf(PrintRate::class);
});

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
