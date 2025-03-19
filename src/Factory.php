<?php

declare(strict_types=1);

namespace Rawilk\Printing;

use BackedEnum;
use Closure;
use Illuminate\Support\Arr;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Enums\PrintDriver;
use Rawilk\Printing\Exceptions\DriverConfigNotFound;
use Rawilk\Printing\Exceptions\UnsupportedDriver;
use SensitiveParameter;

class Factory
{
    protected array $drivers = [];

    /**
     * @var array<string, Closure> An array callback functions to create custom drivers.
     */
    protected array $customCreators = [];

    public function __construct(#[SensitiveParameter] protected array $config)
    {
    }

    public function driver(null|string|PrintDriver $driver = null): Driver
    {
        if ($driver instanceof BackedEnum) {
            $driver = (string) $driver->value;
        }

        if (blank($driver)) {
            $driver = $this->getDefaultDriverName();
        }

        return $this->drivers[$driver] = $this->get($driver);
    }

    public function extend(string $driver, Closure $callback): self
    {
        $this->customCreators[$driver] = $callback;

        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function updateConfig(array $config): void
    {
        $this->config = array_replace_recursive($this->config, $config);

        // Reset our drivers for potential changes to credentials.
        $this->drivers = [];
    }

    protected function createCupsDriver(#[SensitiveParameter] array $config): Driver
    {
        PrintDriver::Cups->ensureConfigIsValid($config);

        return new Drivers\Cups\Cups($config);
    }

    protected function createPrintnodeDriver(#[SensitiveParameter] array $config): Driver
    {
        PrintDriver::PrintNode->ensureConfigIsValid($config);

        return new Drivers\PrintNode\PrintNode($config['key'] ?? null);
    }

    protected function get(string $driver): Driver
    {
        return $this->drivers[$driver] ?? $this->resolve($driver);
    }

    protected function getDefaultDriverName(): string
    {
        return $this->config['driver'] ?? PrintDriver::PrintNode->value;
    }

    protected function getDriverConfig(string $driver): ?array
    {
        return Arr::get($this->config, "drivers.{$driver}");
    }

    protected function resolve(string $driver): Driver
    {
        if (Arr::has($this->drivers, $driver)) {
            return $this->drivers[$driver];
        }

        $config = $this->getDriverConfig($driver);

        if ($this->hasCustomCreator($config['driver'] ?? $driver)) {
            return $this->callCustomCreator($config, $config['driver'] ?? $driver);
        }

        $method = 'create' . ucfirst($driver) . 'Driver';

        throw_unless(
            method_exists($this, $method),
            UnsupportedDriver::driver($driver),
        );

        throw_unless(
            is_array($config),
            DriverConfigNotFound::forDriver($driver),
        );

        return $this->$method($config);
    }

    protected function hasCustomCreator(string $driver): bool
    {
        return Arr::has($this->customCreators, $driver);
    }

    protected function callCustomCreator(?array $config, string $driver): Driver
    {
        return $this->customCreators[$driver]($config);
    }
}
