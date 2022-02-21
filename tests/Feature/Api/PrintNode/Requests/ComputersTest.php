<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Requests\ComputersRequest;

test('can list an accounts computers', function () {
    $this->fakeRequest('computers', 'computers');

    $response = (new ComputersRequest('1234'))->response();

    expect($response->computers)->toHaveCount(3);
    $this->assertContainsOnlyInstancesOf(Computer::class, $response->computers);
});

test('can limit results count', function () {
    $this->fakeRequest('computers*', 'computers_limit');

    $response = (new ComputersRequest('1234'))->response(2);

    expect($response->computers)->toHaveCount(2);
});
