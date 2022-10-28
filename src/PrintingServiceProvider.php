<?php

declare(strict_types=1);

namespace Rawilk\Printing;

use Rawilk\Printing\Api\PrintNode\PrintNode;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PrintingServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-printing')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $printNodeApiKey = $this->app['config']['printing.drivers.printnode.key'];
        $this->app->singleton(PrintNode::class, fn ($app) => new PrintNode((string) $printNodeApiKey));

        $this->app->singleton(
            'printing.factory',
            fn ($app) => new Factory($app['config']['printing'])
        );

        $this->app->singleton('printing.driver', fn ($app) => $app['printing.factory']->driver());

        $this->app->singleton(
            Printing::class,
            fn ($app) => new Printing($app['printing.driver'], $app['config']['printing.default_printer_id'])
        );
    }

    public function provides(): array
    {
        return [
            'printing.factory',
            'printing.driver',
            Printing::class,
        ];
    }
}
