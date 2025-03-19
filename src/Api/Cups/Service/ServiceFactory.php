<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Service;

use Rawilk\Printing\Api\Cups\CupsClientInterface;

/**
 * Service Factory serves two purposes:
 *  1. Expose properties for all services through the `__get()` magic method.
 *  2. Lazily initialize each service instance the first time the property for a given service is used.
 *
 * @internal
 *
 * @property-read \Rawilk\Printing\Api\Cups\Service\PrinterService $printers
 * @property-read \Rawilk\Printing\Api\Cups\Service\PrintJobService $printJobs
 */
class ServiceFactory
{
    protected array $services = [];

    private static array $classMap = [
        'printers' => PrinterService::class,
        'printJobs' => PrintJobService::class,
    ];

    public function __construct(protected CupsClientInterface $client)
    {
    }

    public function __get(string $name): ?AbstractService
    {
        return $this->getService($name);
    }

    public function getService(string $name): ?AbstractService
    {
        $serviceClass = $this->getServiceClass($name);
        if ($serviceClass !== null) {
            if (! array_key_exists($name, $this->services)) {
                $this->services[$name] = new $serviceClass($this->client);
            }

            return $this->services[$name];
        }

        trigger_error('Undefined property ' . static::class . '::$' . $name);

        return null;
    }

    protected function getServiceClass(string $name): ?string
    {
        return self::$classMap[$name] ?? null;
    }
}
