<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\CupsClient;
use Rawilk\Printing\Api\Cups\Service\PrinterService;
use Rawilk\Printing\Api\Cups\Service\ServiceFactory;

beforeEach(function () {
    $client = new CupsClient;
    $this->serviceFactory = new ServiceFactory($client);
});

it('exposes properties for services', function () {
    expect($this->serviceFactory->printers)->toBeInstanceOf(PrinterService::class);
});

test('multiple calls return the same instance', function () {
    $service = $this->serviceFactory->printers;

    expect($this->serviceFactory->printers)->toBe($service);
});
