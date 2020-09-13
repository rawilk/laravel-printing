<?php

declare(strict_types=1);

namespace Rawilk\Printing;

use Closure;
use Illuminate\Support\Arr;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Drivers\Cups\Cups;
use Rawilk\Printing\Drivers\PrintNode\PrintNode;
use Rawilk\Printing\Exceptions\DriverConfigNotFound;
use Rawilk\Printing\Exceptions\InvalidDriverConfig;
use Rawilk\Printing\Exceptions\UnsupportedDriver;

class Factory
{
    protected array $config;
    protected array $drivers = [];
    protected array $customCreators = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function driver(?string $driver = null): Driver
    {
        $driver = $driver ?: $this->getDriverFromConfig();

        return $this->drivers[$driver] = $this->get($driver);
    }

    public function extend(string $driver, Closure $callback): self
    {
        $this->customCreators[$driver] = $callback;

        return $this;
    }

    protected function createCupsDriver(array $config): Driver
    {
        $cups = new Cups;

        if (isset($config['ip'])) {
            $cups->remoteServer($config['ip'], $config['username'], $config['password'], $config['port']);
        }

        return $cups;
    }

    protected function createPrintnodeDriver(array $config): Driver
    {
        if (! isset($config['key']) || empty($config['key'])) {
            throw InvalidDriverConfig::invalid('You must provide an api key for the PrintNode driver.');
        }

        return new PrintNode($config['key']);
    }

    protected function get(string $driver): Driver
    {
        return $this->drivers[$driver] ?? $this->resolve($driver);
    }

    protected function getDriverFromConfig(): string
    {
        return $this->config['driver'] ?? 'printnode';
    }

    protected function getDriverConfig(string $driver): ?array
    {
        return Arr::get($this->config, "drivers.{$driver}");
    }

    protected function resolve(string $driver): Driver
    {
        if (isset($this->drivers[$driver])) {
            return $this->drivers[$driver];
        }

        $config = $this->getDriverConfig($driver);

        if (! is_array($config)) {
            throw DriverConfigNotFound::forDriver($driver);
        }

        if (isset($this->customCreators[$config['driver'] ?? ''])) {
            return $this->callCustomCreator($config);
        }

        if (! method_exists($this, $method = 'create' . ucfirst($driver) . 'Driver')) {
            throw UnsupportedDriver::driver($driver);
        }

        return $this->$method($config);
    }

    protected function callCustomCreator(array $config): Driver
    {
        return $this->customCreators[$config['driver']]($config);
    }
}
