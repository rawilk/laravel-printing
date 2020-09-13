<?php

declare(strict_types=1);

namespace Rawilk\Printing;

use Illuminate\Support\ServiceProvider;

class PrintingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/printing.php' => config_path('printing.php'),
            ], 'config');
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/printing.php', 'printing');

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
