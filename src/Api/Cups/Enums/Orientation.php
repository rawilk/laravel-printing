<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Enums;

enum Orientation: int
{
    case Portrait = 0x03;
    case Landscape = 0x04;
    case ReverseLandscape = 0x05;
    case ReversePortrait = 0x06;
}
