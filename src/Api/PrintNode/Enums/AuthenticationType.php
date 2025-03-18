<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Enums;

enum AuthenticationType: string
{
    case Basic = 'BasicAuth';
    case Digest = 'DigestAuth';
}
