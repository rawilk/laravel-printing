<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Service;

use Illuminate\Support\Collection;
use Rawilk\Printing\Api\PrintNode\Resources\Printer;
use Rawilk\Printing\Api\PrintNode\Resources\PrintJob;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;
use Rawilk\Printing\Exceptions\InvalidArgument;

class PrinterService extends AbstractService
{
    /**
     * Retrieve all printers associated with an account.
     *
     * @param  null|array  $params
     *                              `limit` => the max number of rows that will be returned - default is 100
     *                              `dir` => `asc` for ascending, `desc` for descending - default is `desc`
     *                              `after` => retrieve records with an ID after the provided value
     * @return Collection<int, Printer>
     */
    public function all(?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        return $this->requestCollection('get', '/printers', $params, opts: $opts, expectedResource: Printer::class);
    }

    public function retrieve(int $id, ?array $params = null, null|array|RequestOptions $opts = null): ?Printer
    {
        $printers = $this->requestCollection('get', $this->buildPath('/printers/%s', $id), $params, opts: $opts, expectedResource: Printer::class);

        return $printers->first();
    }

    /**
     * Retrieve a specific set of printers.
     *
     * @param  array<int, int>  $ids  the IDs of the printers to retrieve
     * @param  null|array  $params
     *                              `limit` => the max number of rows that will be returned - default is 100
     *                              `dir` => `asc` for ascending, `desc` for descending - default is `desc`
     *                              `after` => retrieve records with an ID after the provided value
     * @return Collection<int, Printer>
     */
    public function retrieveSet(array $ids, ?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        throw_unless(
            filled($ids),
            InvalidArgument::class,
            'At least one printer ID must be provided for this request.',
        );

        return $this->requestCollection('get', $this->buildPath('/printers/%s', ...$ids), $params, opts: $opts, expectedResource: Printer::class);
    }

    /**
     * Retrieve all print jobs associated with a given printer.
     *
     * @param  int|array  $parentId  the printer's ID; pass an array to retrieve print jobs for multiple printers
     * @param  null|array  $params
     *                              `limit` => the max number of rows that will be returned - default is 100
     *                              `dir` => `asc` for ascending, `desc` for descending - default is `desc`
     *                              `after` => retrieve records with an ID after the provided value
     * @return Collection<int, PrintJob>
     */
    public function printJobs(int|array $parentId, ?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        $path = is_array($parentId)
            ? $this->buildPath('/printers/%s/printjobs', ...$parentId)
            : $this->buildPath('/printers/%s/printjobs', $parentId);

        return $this->requestCollection('get', $path, $params, opts: $opts, expectedResource: PrintJob::class);
    }

    /**
     * Retrieve a single or set of print jobs associated with a given printer.
     *
     * @param  int|array  $parentId  the printer's ID; pass an array to retrieve print jobs for multiple printers
     * @param  int|array  $printJobId  the print job's ID; pass an array to retrieve a set of print jobs
     * @param  null|array  $params
     *                              `limit` => the max number of rows that will be returned - default is 100
     *                              `dir` => `asc` for ascending, `desc` for descending - default is `desc`
     *                              `after` => retrieve records with an ID after the provided value
     * @return Collection<int, PrintJob>|PrintJob|null
     */
    public function printJob(int|array $parentId, int|array $printJobId, ?array $params = null, null|array|RequestOptions $opts = null): Collection|PrintJob|null
    {
        $printerPath = is_array($parentId)
            ? $this->buildPath('/printers/%s', ...$parentId)
            : $this->buildPath('/printers/%s', $parentId);

        $jobPath = is_array($printJobId)
            ? $this->buildPath('/printjobs/%s', ...$printJobId)
            : $this->buildPath('/printjobs/%s', $printJobId);

        $response = $this->requestCollection('get', $printerPath . $jobPath, $params, opts: $opts, expectedResource: PrintJob::class);

        return is_array($printJobId) ? $response : $response->first();
    }
}
