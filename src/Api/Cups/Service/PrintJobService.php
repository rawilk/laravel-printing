<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Service;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Rawilk\Printing\Api\Cups\Enums\Operation;
use Rawilk\Printing\Api\Cups\Enums\OperationAttribute;
use Rawilk\Printing\Api\Cups\Enums\Version;
use Rawilk\Printing\Api\Cups\PendingPrintJob;
use Rawilk\Printing\Api\Cups\PendingRequest;
use Rawilk\Printing\Api\Cups\Resources\PrintJob;
use Rawilk\Printing\Api\Cups\Util\RequestOptions;

class PrintJobService extends AbstractService
{
    /**
     * @return Collection<int, PrintJob>
     */
    public function all(array $params = [], array|null|RequestOptions $opts = null): Collection
    {
        $whichJobs = data_get($params, 'state', 'not-completed');
        unset($params['state']);

        $pendingRequest = (new PendingRequest)
            ->setVersion(Version::V2_1)
            ->setOperation(Operation::GetJobs)
            ->addOperationAttributes([
                OperationAttribute::WhichJobs->value => OperationAttribute::WhichJobs->toType($whichJobs),
                OperationAttribute::RequestedAttributes->value => $params[OperationAttribute::RequestedAttributes->value] ?? PrintJob::defaultRequestedAttributes(),

                ...Arr::except($params, OperationAttribute::RequestedAttributes->value),
            ]);

        return $this->request($pendingRequest, $opts)->jobs();
    }

    /**
     * Create & send a new print job to a printer on a CUPS server.
     */
    public function create(
        PendingPrintJob|PendingRequest $pendingJob,
        array|null|RequestOptions $opts = null,
    ): PrintJob {
        $pendingRequest = $pendingJob instanceof PendingPrintJob
            ? $pendingJob->toPendingRequest()
            : $pendingJob;

        $response = $this->request($pendingRequest, $opts);

        return $response->jobs()->first();
    }

    public function retrieve(string $uri, array $params = [], array|null|RequestOptions $opts = null): ?PrintJob
    {
        $pendingRequest = (new PendingRequest)
            ->setVersion(Version::V2_1)
            ->setOperation(Operation::GetJobAttributes)
            ->addOperationAttributes([
                OperationAttribute::JobUri->value => OperationAttribute::JobUri->toType($uri),
                OperationAttribute::RequestedAttributes->value => $params[OperationAttribute::RequestedAttributes->value] ?? PrintJob::defaultRequestedAttributes(),

                ...Arr::except($params, OperationAttribute::RequestedAttributes->value),
            ]);

        $response = $this->request($pendingRequest, $opts);

        return $response->jobs()->first();
    }
}
