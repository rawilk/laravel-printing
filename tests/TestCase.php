<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests;

use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase as Orchestra;
use Rawilk\Printing\PrintingServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        $this->loadEnvironmentVariables();

        parent::setUp();

        $this->ensureDriversAreConfigured();
    }

    protected function getPackageProviders($app): array
    {
        return [
            PrintingServiceProvider::class,
        ];
    }

    protected function loadEnvironmentVariables(): void
    {
        if (! file_exists(__DIR__ . '/../.env')) {
            return;
        }

        $dotEnv = Dotenv::createImmutable(__DIR__ . '/..');

        $dotEnv->load();
    }

    /**
     * Set fake credentials for drivers if the config values are not set. Useful
     * for PRs from forks that don't have access to repository secrets. This will
     * help prevent value checking from throwing exceptions for missing api keys
     * when they are only needed to create the client instance in the test.
     */
    protected function ensureDriversAreConfigured(): void
    {
        if (blank(config('printing.drivers.printnode.key'))) {
            config()->set('printing.drivers.printnode.key', 'fake');
        }
    }
}
