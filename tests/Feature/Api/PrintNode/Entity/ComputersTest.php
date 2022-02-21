<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Entity\Computers;

test('creates from an array of computer arrays', function () {
    $computers = (new Computers)->setComputers(samplePrintNodeData('computers'));

    expect($computers->computers)->toHaveCount(3);
    $this->assertContainsOnlyInstancesOf(Computer::class, $computers->computers);
});
