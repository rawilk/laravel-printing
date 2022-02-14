<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Api\PrintNode\Requests\ComputersRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

class ComputersTest extends PrintNodeTestCase
{
    /** @test */
    public function can_list_an_accounts_computers(): void
    {
        $this->fakeRequest('computers', 'computers');

        $response = (new ComputersRequest('1234'))->response();

        $this->assertCount(3, $response->computers);
        $this->assertContainsOnlyInstancesOf(Computer::class, $response->computers);
    }

    /** @test */
    public function can_limit_results_count(): void
    {
        $this->fakeRequest('computers*', 'computers_limit');

        $response = (new ComputersRequest('1234'))->response(2);

        $this->assertCount(2, $response->computers);
    }
}
