<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Service;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Rawilk\Printing\Api\Cups\Enums\Operation;
use Rawilk\Printing\Api\Cups\Enums\OperationAttribute;
use Rawilk\Printing\Api\Cups\Enums\Version;
use Rawilk\Printing\Api\Cups\PendingRequest;
use Rawilk\Printing\Api\Cups\Resources\Printer;
use Rawilk\Printing\Api\Cups\Resources\PrintJob;
use Rawilk\Printing\Api\Cups\Util\RequestOptions;

class PrinterService extends AbstractService
{
    /**
     * $params is unused for now, but may be utilized later.
     *
     * @return Collection<int, Printer>
     */
    public function all(array $params = [], array|null|RequestOptions $opts = null): Collection
    {
        $pendingRequest = (new PendingRequest)
            ->setVersion(Version::V2_1)
            ->setOperation(Operation::CupsGetPrinters);

        return $this->request($pendingRequest, $opts)->printers();
    }

    /**
     * $params is unused for now, but may be utilized later.
     */
    public function retrieve(string $uri, array $params = [], array|null|RequestOptions $opts = null): ?Printer
    {
        $pendingRequest = (new PendingRequest)
            ->setVersion(Version::V2_1)
            ->setOperation(Operation::GetPrinterAttributes)
            ->addOperationAttributes([
                OperationAttribute::PrinterUri->value => OperationAttribute::PrinterUri->toType($uri),
            ]);

        $response = $this->request($pendingRequest, $opts);

        return $response->printers()->first();
    }

    public function printJobs(string $parentUri, array $params = [], array|null|RequestOptions $opts = null): Collection
    {
        $whichJobs = data_get($params, 'state', 'not-completed');
        unset($params['state']);

        $pendingRequest = (new PendingRequest)
            ->setVersion(Version::V2_1)
            ->setOperation(Operation::GetJobs)
            ->addOperationAttributes([
                OperationAttribute::PrinterUri->value => OperationAttribute::PrinterUri->toType($parentUri),
                OperationAttribute::WhichJobs->value => OperationAttribute::WhichJobs->toType($whichJobs),
                OperationAttribute::RequestedAttributes->value => $params[OperationAttribute::RequestedAttributes->value] ?? PrintJob::defaultRequestedAttributes(),

                ...Arr::except($params, OperationAttribute::RequestedAttributes->value),
            ]);

        return $this->request($pendingRequest, $opts)->jobs();
    }
}
