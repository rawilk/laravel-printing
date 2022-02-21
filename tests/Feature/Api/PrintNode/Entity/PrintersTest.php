<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Entity\Printers;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);

test('creates from an array of printer arrays', function () {
    $printers = (new Printers)->setPrinters(sampleData());

    expect($printers->printers)->toHaveCount(24);
    $this->assertContainsOnlyInstancesOf(Printer::class, $printers->printers);
});

// Helpers
function sampleData(): array
{
    return json_decode(
        file_get_contents(__DIR__ . '/../../../../stubs/Api/PrintNode/printers.json'),
        true
    );
}
