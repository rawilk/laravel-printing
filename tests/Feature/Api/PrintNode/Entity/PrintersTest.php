<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Entity;

use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Entity\Printers;
use Rawilk\Printing\Tests\TestCase;

class PrintersTest extends TestCase
{
    /** @test */
    public function creates_from_an_array_of_printer_arrays(): void
    {
        $printers = (new Printers)->setPrinters($this->sampleData());

        $this->assertCount(24, $printers->printers);
        $this->assertContainsOnlyInstancesOf(Printer::class, $printers->printers);
    }

    protected function sampleData(): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../../../../stubs/Api/PrintNode/printers.json'),
            true
        );
    }
}
