<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Rawilk\Printing\Api\Cups\Service\ServiceFactory;

/**
 * Client used to send requests to a CUPS server.
 *
 * @property-read \Rawilk\Printing\Api\Cups\Service\PrinterService $printers
 * @property-read \Rawilk\Printing\Api\Cups\Service\PrintJobService $printJobs
 */
class CupsClient extends BaseCupsClient
{
    private ?ServiceFactory $serviceFactory = null;

    public function __get(string $name): ?Service\AbstractService
    {
        return $this->getService($name);
    }

    public function getService(string $name): ?Service\AbstractService
    {
        if ($this->serviceFactory === null) {
            $this->serviceFactory = new ServiceFactory($this);
        }

        return $this->serviceFactory->getService($name);
    }
}
