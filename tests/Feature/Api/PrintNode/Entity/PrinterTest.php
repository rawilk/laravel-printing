<?php

declare(strict_types=1);

use Carbon\Carbon;
use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Entity\PrinterCapabilities;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);

test('can be create from an array', function () {
    $printer = new Printer(sampleData());

    $this->assertSame(39, $printer->id);
    $this->assertInstanceOf(Computer::class, $printer->computer);
    $this->assertSame(13, $printer->computer->id);
    $this->assertEquals('Microsoft XPS Document Writer', $printer->name);
    $this->assertEquals('Microsoft XPS Document Writer', $printer->description);
    $this->assertInstanceOf(PrinterCapabilities::class, $printer->capabilities);
    $this->assertEquals(['Automatically Select'], $printer->capabilities->bins);
    $this->assertEquals($printer->trays(), $printer->capabilities->bins);
    $this->assertTrue($printer->isOnline());
    $this->assertInstanceOf(Carbon::class, $printer->created);
    $this->assertEquals('2015-11-17 13:02:37', $printer->created->format('Y-m-d H:i:s'));
});

test('casts to array', function () {
    $data = sampleData();
    $printer = new Printer($data);

    $asArray = $printer->toArray();

    foreach ($data as $key => $value) {
        $this->assertArrayHasKey($key, $asArray);
    }

    $this->assertIsArray($asArray['computer']);
    $this->assertIsArray($asArray['capabilities']);
    $this->assertArrayHasKey('createTimestamp', $asArray['computer']);
});

// Helpers
function sampleData(): array
{
    return json_decode(
        file_get_contents(__DIR__ . '/../../../../stubs/Api/PrintNode/printer_single.json'),
        true
    )[0];
}
