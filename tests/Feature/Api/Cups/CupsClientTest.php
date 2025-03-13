<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\CupsClient;
use Rawilk\Printing\Api\Cups\Service\PrinterService;

beforeEach(function () {
    $this->obj = new CupsClient;
});

it('exposes properties for services', function () {
    expect($this->obj->printers)->toBeInstanceOf(PrinterService::class);
});
