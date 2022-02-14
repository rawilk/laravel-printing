<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Drivers\PrintNode;

use Rawilk\Printing\Drivers\PrintNode\Entity\Printer;
use Rawilk\Printing\Drivers\PrintNode\PrintNode;
use Rawilk\Printing\Tests\Concerns\FakesPrintNodeRequests;
use Rawilk\Printing\Tests\TestCase;

class PrintNodeTest extends TestCase
{
    use FakesPrintNodeRequests;

    protected PrintNode $printNode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->printNode = new PrintNode;
    }

    /** @test */
    public function it_lists_an_accounts_printers(): void
    {
        $this->fakeRequest('printers', 'printers');

        $printers = $this->printNode->printers();

        $this->assertCount(24, $printers);
        $this->assertContainsOnlyInstancesOf(Printer::class, $printers);
    }

    /** @test */
    public function finds_an_accounts_printer(): void
    {
        $this->fakeRequest('printers/39', 'printer_single');

        $printer = $this->printNode->printer(39);

        $this->assertSame(39, $printer->id());
        $this->assertEquals(['Automatically Select'], $printer->trays());
        $this->assertEquals('Microsoft XPS Document Writer', $printer->name());
        $this->assertTrue($printer->isOnline());
    }

    /** @test */
    public function returns_null_for_no_printer_found(): void
    {
        $this->fakeRequest('printers/1234', 'printer_single_not_found');

        $printer = $this->printNode->printer(1234);

        $this->assertNull($printer);
    }
}
