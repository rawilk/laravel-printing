<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Api\PrintNode;

use Closure;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\PrintNode;

trait FakesPrintNodeRequests
{
    protected static int $fakeResponseCode = 200;

    /**
     * Either a string for a json stub file, or a callback to return some data
     * for a stubbed request. This has to be static, otherwise when all tests
     * are run together they will fail.
     */
    protected static string|null|Closure $fakeCallback = null;

    /**
     * A custom callback that allows us to perform extra expectations against
     * the current stubbed request.
     */
    protected static ?Closure $fakeRequestExpectation = null;

    protected function fakeRequests(): void
    {
        Http::fake([
            PrintNode::$apiBase . '/*' => function (Request $request) {
                $content = is_callable(static::$fakeCallback)
                    ? call_user_func(static::$fakeCallback)
                    : samplePrintNodeData(static::$fakeCallback);

                if (is_callable(static::$fakeRequestExpectation)) {
                    call_user_func(static::$fakeRequestExpectation, $request);
                }

                return Http::response($content, status: static::$fakeResponseCode);
            },
        ]);
    }

    protected function fakeRequest(string|null|Closure $callback, int $code = 200, ?Closure $expectation = null): void
    {
        static::$fakeCallback = $callback;
        static::$fakeRequestExpectation = $expectation;
        static::$fakeResponseCode = $code;
    }
}
