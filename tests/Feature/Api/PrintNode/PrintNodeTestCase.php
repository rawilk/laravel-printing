<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode;

use Rawilk\Printing\Tests\Concerns\FakesPrintNodeRequests;
use Rawilk\Printing\Tests\TestCase;

abstract class PrintNodeTestCase extends TestCase
{
    use FakesPrintNodeRequests;

    protected string $apiKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiKey = config('printing.drivers.printnode.key');
    }
}
