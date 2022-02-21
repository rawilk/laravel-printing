<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Requests\PrintersRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

uses(PrintNodeTestCase::class);

test('lists an accounts printers', function () {
    $this->fakeRequest('printers', 'printers');

    $response = (new PrintersRequest('1234'))->response();

    $this->assertCount(24, $response->printers);
    $this->assertContainsOnlyInstancesOf(Printer::class, $response->printers);
});

test('can limit results count', function () {
    $this->fakeRequest('printers*', 'printers_limit');

    $response = (new PrintersRequest('1234'))->response(3);

    $this->assertCount(3, $response->printers);
});
