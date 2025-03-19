<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode;

interface BasePrintNodeClientInterface
{
    /**
     * Gets the API key used by the client to send api requests.
     */
    public function getApiKey(): ?string;

    /**
     * Gets the base URL for PrintNode's API.
     */
    public function getApiBase(): string;
}
