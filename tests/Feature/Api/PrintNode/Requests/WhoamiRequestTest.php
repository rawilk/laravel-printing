<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Requests\WhoamiRequest;
use Rawilk\Printing\Exceptions\PrintNodeApiRequestFailed;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\PrintNodeTestCase;

class WhoamiRequestTest extends PrintNodeTestCase
{
    /** @test */
    public function gets_account_info(): void
    {
        $this->fakeRequest('whoami', 'whoami');

        $whoami = (new WhoamiRequest('1234'))->response();

        $this->assertSame(433, $whoami->id);
        $this->assertEquals('Peter', $whoami->firstName);
        $this->assertEquals('Tuthill', $whoami->lastName);
        $this->assertEquals('active', $whoami->state);
        $this->assertSame(10134, $whoami->credits);
    }

    /** @test */
    public function invalid_api_key_does_not_work(): void
    {
        $this->fakeRequest('whoami', 'whoami_bad_api_key', 401);

        $this->expectException(PrintNodeApiRequestFailed::class);
        $this->expectExceptionCode(401);
        $this->expectExceptionMessage('API Key not found');

        // We are sending an actual api request here!
        (new WhoamiRequest('foo'))->response();
    }

    /** @test */
    public function actual_requests_can_be_made(): void
    {
        $whoami = (new WhoamiRequest($this->apiKey))->response();

        $this->assertEquals(env('PRINT_NODE_ID'), $whoami->id);
    }
}
