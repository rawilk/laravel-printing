<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Requests;

use Carbon\Carbon;
use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Entity\PrinterCapabilities;
use Rawilk\Printing\Api\PrintNode\Requests\PrinterRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

class PrinterTest extends PrintNodeTestCase
{
    /** @test */
    public function can_find_an_accounts_printer(): void
    {
        $this->fakeRequest('printers/39', 'printer_single');

        $printer = (new PrinterRequest('1234'))->response(39);

        $this->assertNotNull($printer);
        $this->assertSame(39, $printer->id);
        $this->assertInstanceOf(Computer::class, $printer->computer);
        $this->assertEquals('Microsoft XPS Document Writer', $printer->name);
        $this->assertEquals('Microsoft XPS Document Writer', $printer->description);
        $this->assertInstanceOf(PrinterCapabilities::class, $printer->capabilities);
        $this->assertEquals(['Automatically Select'], $printer->trays());
        $this->assertInstanceOf(Carbon::class, $printer->created);
        $this->assertEquals('2015-11-17 13:02:37', $printer->created->format('Y-m-d H:i:s'));
        $this->assertEquals('online', $printer->state);
        $this->assertTrue($printer->isOnline());
        $this->assertFalse($printer->isCollate());
        $this->assertTrue($printer->isColor());
        $this->assertSame(1, $printer->copies());
    }

    /** @test */
    public function printer_knows_if_printnode_says_it_is_offline(): void
    {
        $this->fakeRequest('printers/40', 'printer_single_offline');

        $printer = (new PrinterRequest('1234'))->response(40);

        $this->assertFalse($printer->isOnline());
    }

    /** @test */
    public function printer_capabilities_will_always_be_available(): void
    {
        $this->fakeRequest('printers/34', 'printer_single_no_capabilities');

        $printer = (new PrinterRequest('1234'))->response(34);

        $this->assertInstanceOf(PrinterCapabilities::class, $printer->capabilities);
        $this->assertIsArray($printer->trays());
        $this->assertEmpty($printer->trays());
    }

    /** @test */
    public function returns_null_for_no_printer_found(): void
    {
        $this->fakeRequest('printers/1234', 'printer_single_not_found');

        $printer = (new PrinterRequest('1234'))->response(1234);

        $this->assertNull($printer);
    }
}
