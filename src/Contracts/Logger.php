<?php

declare(strict_types=1);

namespace Rawilk\Printing\Contracts;

interface Logger
{
    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     */
    public function error(string $message, array $context = []);
}
