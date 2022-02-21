<?php

declare(strict_types=1);

use Carbon\Carbon;
use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Requests\ComputerRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

uses(PrintNodeTestCase::class);

test('can find an accounts computer', function () {
    $this->fakeRequest('computers/14', 'computer_single');

    $computer = (new ComputerRequest('1234'))->response(14);

    $this->assertNotNull($computer);
    $this->assertSame(14, $computer->id);
    $this->assertInstanceOf(Computer::class, $computer);
    $this->assertSame('TUNGSTEN', $computer->name);
    $this->assertEquals('192.168.56.1', $computer->inet);
    $this->assertEquals('Pete@TUNGSTEN', $computer->hostName);
    $this->assertEquals('disconnected', $computer->state);
    $this->assertInstanceOf(Carbon::class, $computer->created);
    $this->assertEquals('2015-11-17 16:06:24', $computer->created->format('Y-m-d H:i:s'));
});

test('returns null for no computer found', function () {
    $this->fakeRequest('computers/1234', 'computer_single_not_found');

    $computer = (new ComputerRequest('1234'))->response(1234);

    $this->assertNull($computer);
});
