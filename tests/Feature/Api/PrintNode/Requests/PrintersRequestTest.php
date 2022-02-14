<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\Printer;
use Rawilk\Printing\Api\PrintNode\Requests\PrintersRequest;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

class PrintersRequestTest extends PrintNodeTestCase
{
    /** @test */
    public function lists_an_accounts_printers(): void
    {
        $this->fakeRequest('printers', 'printers');

        $response = (new PrintersRequest('1234'))->response();

        $this->assertCount(24, $response->printers);
        $this->assertContainsOnlyInstancesOf(Printer::class, $response->printers);
    }

    /** @test */
    public function can_limit_results_count(): void
    {
        $this->fakeRequest('printers*', 'printers_limit');

        $response = (new PrintersRequest('1234'))->response(3);

        $this->assertCount(3, $response->printers);
    }
}
