<?php

namespace Rawilk\Printing\Api\Cups;

enum Version: string
{
    case V1_0 = '1.0';
    case V1_1 = '1.1';

    public function encode(): string
    {
        $version = explode('.', (string) $this->value);
        return pack('c', $version[0]) . pack('c', $version[1]);
    }
}
