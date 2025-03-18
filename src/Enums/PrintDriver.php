<?php

declare(strict_types=1);

namespace Rawilk\Printing\Enums;

use Rawilk\Printing\Exceptions\InvalidDriverConfig;

/**
 * Printing drivers supported by the package.
 */
enum PrintDriver: string
{
    case PrintNode = 'printnode';
    case Cups = 'cups';

    public function ensureConfigIsValid(array $config): void
    {
        $method = 'validate' . ucfirst($this->value) . 'Config';

        $this->{$method}($config);
    }

    protected function validatePrintnodeConfig(array $config): void
    {
        $key = data_get($config, 'key');

        // We'll attempt to fall back on the static PrintNode::$apiKey value later.
        if ($key === null) {
            return;
        }

        throw_if(
            blank($key),
            InvalidDriverConfig::invalid('You must provide an api key for the PrintNode driver.'),
        );
    }

    protected function validateCupsConfig(array $config): void
    {
        $ip = data_get($config, 'ip');
        throw_if(
            $ip !== null && blank($ip),
            InvalidDriverConfig::invalid('An IP address is required for the CUPS driver.'),
        );

        $secure = data_get($config, 'secure');
        throw_if(
            $secure !== null && (! is_bool($secure)),
            InvalidDriverConfig::invalid('A boolean value must be provided for the secure option for the CUPS driver.'),
        );

        $port = data_get($config, 'port');
        throw_if(
            $port !== null && blank($port),
            InvalidDriverConfig::invalid('A port must be provided for the CUPS driver.'),
        );

        throw_if(
            $port !== null &&
            ((! is_int($port)) || $port < 1),
            InvalidDriverConfig::invalid('A valid port number was not provided for the CUPS driver.'),
        );
    }
}
