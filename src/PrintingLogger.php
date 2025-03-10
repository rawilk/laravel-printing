<?php

declare(strict_types=1);

namespace Rawilk\Printing;

use Psr\Log\LoggerInterface;
use Rawilk\Printing\Contracts\Logger;

class PrintingLogger implements Logger
{
    public function __construct(protected LoggerInterface $logger)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function error(string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }
}
