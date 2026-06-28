<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode;

use Rawilk\Printing\Api\PrintNode\Service\ComputerService;
use Rawilk\Printing\Api\PrintNode\Service\PrinterService;
use Rawilk\Printing\Api\PrintNode\Service\PrintJobService;
use Rawilk\Printing\Api\PrintNode\Service\ServiceFactory;
use Rawilk\Printing\Api\PrintNode\Service\WhoamiService;

/**
 * Client used to send requests to PrintNode's API.
 *
 * @property-read ComputerService $computers
 * @property-read PrinterService $printers
 * @property-read PrintJobService $printJobs
 * @property-read WhoamiService $whoami
 */
class PrintNodeClient extends BasePrintNodeClient
{
    private ?ServiceFactory $serviceFactory = null;

    public function __get(string $name)
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
