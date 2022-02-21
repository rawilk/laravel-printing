<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Entity\Printers;

test('creates from an array of printer arrays', function () {
    $printers = (new Printers)->setPrinters(samplePrintNodeData('printers'));

    expect($printers->printers)->toHaveCount(24);
    $this->assertContainsOnlyInstancesOf(Printer::class, $printers->printers);
});
