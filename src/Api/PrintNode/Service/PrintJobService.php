<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Service;

use Illuminate\Support\Collection;
use Rawilk\Printing\Api\PrintNode\PendingPrintJob;
use Rawilk\Printing\Api\PrintNode\Resources\PrintJob;
use Rawilk\Printing\Api\PrintNode\Resources\PrintJobState;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;
use Rawilk\Printing\Exceptions\InvalidArgument;
use Rawilk\Printing\Exceptions\PrintTaskFailed;

class PrintJobService extends AbstractService
{
    /**
     * Retrieve all print jobs associated with an account.
     *
     * @param  null|array  $params
     *                              `limit` => the max number of rows that will be returned - default is 100
     *                              `dir` => `asc` for ascending, `desc` for descending - default is `desc`
     *                              `after` => retrieve records with an ID after the provided value
     * @return Collection<int, PrintJob>
     */
    public function all(?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        return $this->requestCollection('get', '/printjobs', $params, opts: $opts, expectedResource: PrintJob::class);
    }

    /**
     * Create a new PrintJob for PrintNode to send to a physical printer. We have to perform a separate API
     * request to retrieve the newly created print job because PrintNode only returns the ID of the job
     * that was just created.
     */
    public function create(array|PendingPrintJob $params, null|array|RequestOptions $opts = null): PrintJob
    {
        $data = $params instanceof PendingPrintJob ? $params->toArray() : $params;

        $jobId = $this->request('post', '/printjobs', $data, opts: $opts);

        throw_unless(
            filled($jobId),
            PrintTaskFailed::noJobCreated(),
        );

        return $this->retrieve($jobId, opts: $opts);
    }

    public function retrieve(int $id, ?array $params = null, null|array|RequestOptions $opts = null): ?PrintJob
    {
        $jobs = $this->requestCollection('get', $this->buildPath('/printjobs/%s', $id), $params, opts: $opts, expectedResource: PrintJob::class);

        return $jobs->first();
    }

    /**
     * Retrieve a specific set of print jobs.
     *
     * @param  array<int, int>  $ids  the IDs of the print jobs to retrieve
     * @param  null|array  $params
     *                              `limit` => the max number of rows that will be returned - default is 100
     *                              `dir` => `asc` for ascending, `desc` for descending - default is `desc`
     *                              `after` => retrieve records with an ID after the provided value
     * @return Collection<int, PrintJob>
     */
    public function retrieveSet(array $ids, ?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        throw_unless(
            filled($ids),
            InvalidArgument::class,
            'At least one print job ID must be provided for this request.',
        );

        return $this->requestCollection('get', $this->buildPath('/printjobs/%s', ...$ids), $params, opts: $opts, expectedResource: PrintJob::class);
    }

    /**
     * Retrieve all print job states for an account.
     *
     * Note: if `limit` is passed in as a `$param`, it applies to the amount of print jobs to retrieve
     * states for. For example, if there are 3 print jobs with 5 states each, and a limit of 2 is
     * specified, a total of 10 print job states will be received.
     *
     * @param  null|array  $params
     *                              `limit` => the max number of rows that will be returned - default is 100
     *                              `dir` => `asc` for ascending, `desc` for descending - default is `desc`
     *                              `after` => retrieve records with an ID after the provided value
     * @return Collection<int, PrintJobState>
     */
    public function states(?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        return $this->requestCollection('get', '/printjobs/states', $params, opts: $opts, expectedResource: PrintJobState::class)->flatten();
    }

    /**
     * Retrieve the print job states for a given print job.
     *
     * Note: If `limit` is passed in as a `$param`, it applies to the amount of print jobs to retrieve states for.
     *
     * @param  int|array  $parentId  the ID of the print job to fetch states for; use an array for multiple print jobs
     * @param  null|array  $params
     *                              `limit` => the max number of rows that will be returned - default is 100
     *                              `dir` => `asc` for ascending, `desc` for descending - default is `desc`
     *                              `after` => retrieve records with an ID after the provided value
     * @return Collection<int, PrintJobState>
     */
    public function statesFor(int|array $parentId, ?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        $path = is_array($parentId)
            ? $this->buildPath('/printjobs/%s/states', ...$parentId)
            : $this->buildPath('/printjobs/%s/states', $parentId);

        return $this->requestCollection('get', $path, $params, opts: $opts, expectedResource: PrintJobState::class)->flatten();
    }

    /**
     * Cancel (delete) a set of pending print jobs. Returns an array of affected IDs.
     * Omit or use an empty array of `$ids` to delete all jobs.
     */
    public function cancelMany(array $ids = [], ?array $params = null, null|array|RequestOptions $opts = null): array
    {
        $path = filled($ids)
            ? $this->buildPath('/printjobs/%s', ...$ids)
            : '/printjobs';

        return $this->request('delete', $path, $params, opts: $opts);
    }

    /**
     * Cancel (delete) a given pending print job. Returns an array of affected IDs.
     */
    public function cancel(int $id, ?array $params = null, null|array|RequestOptions $opts = null): array
    {
        return $this->request('delete', $this->buildPath('/printjobs/%s', $id), $params, opts: $opts);
    }
}
