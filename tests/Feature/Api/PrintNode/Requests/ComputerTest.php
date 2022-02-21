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
    expect($computer->id)->toBe(14);
    expect($computer)->toBeInstanceOf(Computer::class);
    expect($computer->name)->toBe('TUNGSTEN');
    expect($computer->inet)->toEqual('192.168.56.1');
    expect($computer->hostName)->toEqual('Pete@TUNGSTEN');
    expect($computer->state)->toEqual('disconnected');
    expect($computer->created)->toBeInstanceOf(Carbon::class);
    expect($computer->created->format('Y-m-d H:i:s'))->toEqual('2015-11-17 16:06:24');
});

test('returns null for no computer found', function () {
    $this->fakeRequest('computers/1234', 'computer_single_not_found');

    $computer = (new ComputerRequest('1234'))->response(1234);

    expect($computer)->toBeNull();
});
