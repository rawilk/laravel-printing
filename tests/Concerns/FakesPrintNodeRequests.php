<?php

namespace Rawilk\Printing\Tests\Concerns;

use Illuminate\Support\Facades\Http;

trait FakesPrintNodeRequests
{
    protected function fakeRequest(string $service, string $stub, int $code = 200): void
    {
        Http::fake([
            "https://api.printnode.com/{$service}" => Http::response(json_decode(file_get_contents(__DIR__ . "/../stubs/Api/PrintNode/{$stub}.json"), true), $code),
        ]);
    }
}
