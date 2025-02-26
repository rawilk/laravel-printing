<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

enum Version: string
{
    case V1_0 = '1.0';
    case V1_1 = '1.1';
    case V2_0 = '2.0';
    case V2_1 = '2.1';

    public function encode(): string
    {
        $version = explode('.', (string) $this->value);

        return pack('c', $version[0]) . pack('c', $version[1]);
    }
}
