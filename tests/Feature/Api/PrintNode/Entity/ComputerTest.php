<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode\Entity;

use Carbon\Carbon;
use Rawilk\Printing\Api\PrintNode\Entity\Computer;
use Rawilk\Printing\Tests\TestCase;

class ComputerTest extends TestCase
{
    /** @test */
    public function can_be_created_from_array(): void
    {
        $computer = new Computer($this->sampleData());

        $this->assertSame(14, $computer->id);
        $this->assertEquals('TUNGSTEN', $computer->name);
        $this->assertEquals('192.168.56.1', $computer->inet);
        $this->assertEquals('Pete@TUNGSTEN', $computer->hostName);
        $this->assertEquals('disconnected', $computer->state);
        $this->assertInstanceOf(Carbon::class, $computer->created);
        $this->assertEquals('2015-11-17 16:06:24', $computer->created->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function can_be_cast_to_array(): void
    {
        $data = $this->sampleData();
        $computer = new Computer($data);

        $asArray = $computer->toArray();

        foreach ($data as $key => $value) {
            if ($key === 'hostname') {
                $key = 'hostName';
            }

            $this->assertArrayHasKey($key, $asArray);
        }
    }

    protected function sampleData(): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../../../../stubs/Api/PrintNode/computer_single.json'),
            true
        )[0];
    }
}
