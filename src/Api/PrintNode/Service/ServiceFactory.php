<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Service;

use Rawilk\Printing\Api\PrintNode\PrintNodeClientInterface;

/**
 * Service Factory serves two purposes:
 * 1. Expose properties for all services through the `__get()` magic method.
 * 2. Lazily initialize each service instance the first time the property for a given service is used.
 *
 * @internal
 *
 * @property-read ComputerService $computers
 * @property-read PrinterService $printers
 * @property-read PrintJobService $printJobs
 * @property-read WhoamiService $whoami
 */
class ServiceFactory
{
    protected array $services = [];

    private static array $classMap = [
        'computers' => ComputerService::class,
        'printers' => PrinterService::class,
        'printJobs' => PrintJobService::class,
        'whoami' => WhoamiService::class,
    ];

    public function __construct(protected PrintNodeClientInterface $client)
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

        trigger_error('Undefined property: ' . static::class . '::$' . $name);

        return null;
    }

    protected function getServiceClass(string $name): ?string
    {
        return self::$classMap[$name] ?? null;
    }
}
