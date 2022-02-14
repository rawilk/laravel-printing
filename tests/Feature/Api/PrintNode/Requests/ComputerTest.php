<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Requests;

use Carbon\Carbon;
use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Requests\ComputerRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

class ComputerTest extends PrintNodeTestCase
{
    /** @test */
    public function can_find_an_accounts_computer(): void
    {
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
    }

    /** @test */
    public function returns_null_for_no_computer_found(): void
    {
        $this->fakeRequest('computers/1234', 'computer_single_not_found');

        $computer = (new ComputerRequest('1234'))->response(1234);

        $this->assertNull($computer);
    }
}
