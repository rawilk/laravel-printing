<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Drivers\PrintNode;

use Mockery;
use PrintNode\Client;
use Rawilk\Printing\Drivers\PrintNode\PrintNode;
use Rawilk\Printing\Tests\TestCase;

class PrintTaskTest extends TestCase
{
    protected PrintNode $printNode;
    protected $mockedClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->printNode = new PrintNode(config('printing.drivers.printnode.key'));
        $this->mockedClient = Mockery::mock(Client::class);
        $this->printNode->setClient($this->mockedClient);
    }

    /** @test */
    public function it_returns_the_print_job_id_on_a_successful_print_job(): void
    {
        $this->mockedClient
            ->shouldReceive('createPrintJob')
            ->andReturn(123456);

        $job = $this->printNode
            ->newPrintTask()
            ->printer('printer-id')
            ->content('foo')
            ->send();

        self::assertEquals(123456, $job->id());
    }
}
