<?php

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
}
