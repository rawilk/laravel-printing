<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Entity;

use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Entity\Computers;
use Rawilk\Printing\Tests\TestCase;

class ComputersTest extends TestCase
{
    /** @test */
    public function creates_from_an_array_of_computer_arrays(): void
    {
        $computers = (new Computers)->setComputers($this->sampleData());

        $this->assertCount(3, $computers->computers);
        $this->assertContainsOnlyInstancesOf(Computer::class, $computers->computers);
    }

    protected function sampleData(): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../../../../stubs/Api/PrintNode/computers.json'),
            true
        );
    }
}
