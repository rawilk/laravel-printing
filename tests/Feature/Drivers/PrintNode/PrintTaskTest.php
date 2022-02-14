<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Drivers\PrintNode;

use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Drivers\PrintNode\PrintNode;
use Rawilk\Printing\Exceptions\PrintTaskFailed;
use Rawilk\Printing\Tests\Concerns\FakesPrintNodeRequests;
use Rawilk\Printing\Tests\TestCase;

class PrintTaskTest extends TestCase
{
    use FakesPrintNodeRequests;

    protected PrintNode $printNode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->printNode = new PrintNode;
    }

    /** @test */
    public function it_returns_the_print_job_id_on_a_successful_print_job(): void
    {
        Http::fake([
            'https://api.printnode.com/printjobs' => Http::response(473),
        ]);

        $this->fakeRequest('printjobs/473', 'print_job_single');

        $job = $this->printNode
            ->newPrintTask()
            ->printer(33)
            ->content('foo')
            ->send();

        $this->assertEquals(473, $job->id());
    }

    /** @test */
    public function printer_id_is_required(): void
    {
        $this->expectException(PrintTaskFailed::class);
        $this->expectExceptionMessage('A printer must be specified to print!');

        $this->printNode
            ->newPrintTask()
            ->content('foo')
            ->send();
    }

    /** @test */
    public function print_source_is_required(): void
    {
        $this->expectException(PrintTaskFailed::class);
        $this->expectExceptionMessage('A print source must be specified!');

        $this->printNode
            ->newPrintTask()
            ->printSource('')
            ->printer(33)
            ->content('foo')
            ->send();
    }

    /** @test */
    public function content_type_is_required(): void
    {
        $this->expectException(PrintTaskFailed::class);
        $this->expectExceptionMessage('Content type must be specified for this driver!');

        $this->printNode
            ->newPrintTask()
            ->printer(33)
            ->send();
    }
}
