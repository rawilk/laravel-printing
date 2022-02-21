<?php

declare(strict_types=1);

use Carbon\Carbon;
use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);

test('can be created from array', function () {
    $computer = new Computer(sampleData());

    expect($computer->id)->toBe(14);
    expect($computer->name)->toEqual('TUNGSTEN');
    expect($computer->inet)->toEqual('192.168.56.1');
    expect($computer->hostName)->toEqual('Pete@TUNGSTEN');
    expect($computer->state)->toEqual('disconnected');
    expect($computer->created)->toBeInstanceOf(Carbon::class);
    expect($computer->created->format('Y-m-d H:i:s'))->toEqual('2015-11-17 16:06:24');
});

test('can be cast to array', function () {
    $data = sampleData();
    $computer = new Computer($data);

    $asArray = $computer->toArray();

    foreach ($data as $key => $value) {
        if ($key === 'hostname') {
            $key = 'hostName';
        }

        $this->assertArrayHasKey($key, $asArray);
    }
});

// Helpers
function sampleData(): array
{
    return json_decode(
        file_get_contents(__DIR__ . '/../../../../stubs/Api/PrintNode/computer_single.json'),
        true
    )[0];
}
