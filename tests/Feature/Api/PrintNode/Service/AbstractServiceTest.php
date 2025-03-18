<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\PrintNodeClient;
use Rawilk\Printing\Api\PrintNode\Service\AbstractService;

beforeEach(function () {
    $this->client = new PrintNodeClient(['api_key' => 'my-key']);

    $this->service = new class($this->client) extends AbstractService
    {
    };
});

test('buildPath replaces IDs properly', function (int|array $id, string $expectedUrl) {
    $path = is_array($id)
        ? invade($this->service)->buildPath('/printers/%s', ...$id)
        : invade($this->service)->buildPath('/printers/%s', $id);

    expect($path)->toBe($expectedUrl);
})->with([
    'single id' => fn () => ['id' => 1234, 'expectedUrl' => '/printers/1234'],
    'multiple ids' => fn () => ['id' => [1234, 4321, 9999], 'expectedUrl' => '/printers/1234,4321,9999'],
]);
