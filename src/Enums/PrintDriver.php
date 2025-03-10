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
            blank(data_get($config, 'key')),
            InvalidDriverConfig::invalid('You must provide an api key for the PrintNode driver.'),
        );
    }

    protected function validateCupsConfig(array $config): void
    {
    }
}
