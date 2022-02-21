<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Entity\Computers;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);

test('creates from an array of computer arrays', function () {
    $computers = (new Computers)->setComputers(sampleData());

    $this->assertCount(3, $computers->computers);
    $this->assertContainsOnlyInstancesOf(Computer::class, $computers->computers);
});

// Helpers
function sampleData(): array
{
    return json_decode(
        file_get_contents(__DIR__ . '/../../../../stubs/Api/PrintNode/computers.json'),
        true
    );
}
