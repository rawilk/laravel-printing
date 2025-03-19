<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Resources\Support\PrintRate;

test('print rate data', function () {
    $rate = PrintRate::make([
        'unit' => 'ppm',
        'rate' => 20,
    ]);

    expect($rate)
        ->unit->toBe('ppm')
        ->rate->toBe(20);
});
