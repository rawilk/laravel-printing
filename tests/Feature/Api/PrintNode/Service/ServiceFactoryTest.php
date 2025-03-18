<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\PrintNodeClient;
use Rawilk\Printing\Api\PrintNode\Service\ServiceFactory;
use Rawilk\Printing\Api\PrintNode\Service\WhoamiService;

beforeEach(function () {
    $this->client = new PrintNodeClient(config('printing.drivers.printnode.key'));
    $this->serviceFactory = new ServiceFactory($this->client);
});

it('exposes properties for services', function () {
    expect($this->serviceFactory->whoami)->toBeInstanceOf(WhoamiService::class);
});

test('multiple calls return the same instance', function () {
    $service = $this->serviceFactory->whoami;

    expect($this->serviceFactory->whoami)->toBe($service);
});
