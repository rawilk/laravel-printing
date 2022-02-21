<?php

declare(strict_types=1);

use Carbon\Carbon;
use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);

test('can be created from array', function () {
    $computer = new Computer(sampleData());

    $this->assertSame(14, $computer->id);
    $this->assertEquals('TUNGSTEN', $computer->name);
    $this->assertEquals('192.168.56.1', $computer->inet);
    $this->assertEquals('Pete@TUNGSTEN', $computer->hostName);
    $this->assertEquals('disconnected', $computer->state);
    $this->assertInstanceOf(Carbon::class, $computer->created);
    $this->assertEquals('2015-11-17 16:06:24', $computer->created->format('Y-m-d H:i:s'));
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
