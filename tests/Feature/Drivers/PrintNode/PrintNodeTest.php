<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Drivers\PrintNode;

use Illuminate\Support\Collection;
use Rawilk\Printing\Drivers\PrintNode\Entity\Printer;
use Rawilk\Printing\Drivers\PrintNode\PrintNode;
use Rawilk\Printing\Tests\TestCase;

class PrintNodeTest extends TestCase
{
    protected PrintNode $printNode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->printNode = new PrintNode(config('printing.drivers.printnode.key'));
    }

    /** @test */
    public function it_lists_an_accounts_printers(): void
    {
        $printers = $this->printNode->printers();

        self::assertInstanceOf(Collection::class, $printers);
        self::assertContainsOnlyInstancesOf(Printer::class, $printers);
    }
}
