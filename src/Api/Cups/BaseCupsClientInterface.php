<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

interface BaseCupsClientInterface
{
    /**
     * Gets the IP address used by the client to send requests.
     */
    public function getIp(): ?string;

    /**
     * Get a Tuple of the authentication used by the client for the CUPS server.
     *
     * @return array Tuple containing the username and password
     */
    public function getAuth(): array;

    /**
     * Gets the port used by the client to send requests.
     */
    public function getPort(): ?int;

    /**
     * Gets the secure setting used by the client to send requests.
     */
    public function getSecure(): ?bool;
}
