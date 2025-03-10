<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode;

readonly class PrintNodeApiResponse
{
    public function __construct(
        public int $code,
        public array|int $body,
        public array $headers,
    ) {
    }
}
