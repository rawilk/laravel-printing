<?php

declare(strict_types=1);

namespace Rawilk\Printing;

use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Logger;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class PrintingServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-printing')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(
            Factory::class,
            fn ($app) => new Factory($app['config']['printing'])
        );

        $this->app->singleton(Driver::class, fn ($app) => $app[Factory::class]->driver());

        $this->app->singleton(
            Printing::class,
            fn ($app) => new Printing($app[Driver::class], $app['config']['printing.default_printer_id'])
        );

        $this->bindLogger();
    }

    public function packageBooted(): void
    {
        $this->registerLogger();
    }

    public function provides(): array
    {
        return [
            Factory::class,
            Driver::class,
            Printing::class,
        ];
    }

    private function bindLogger(): void
    {
        $this->app->bind(
            Logger::class,
            fn ($app) => new PrintingLogger($app->make('log')->channel(config('printing.logger'))),
        );
    }

    private function registerLogger(): void
    {
        if (config('printing.logger')) {
            Printing::setLogger($this->app->make(Logger::class));
        }
    }
}
