<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Service;

use Illuminate\Support\Collection;
use Rawilk\Printing\Api\PrintNode\Resources\Computer;
use Rawilk\Printing\Api\PrintNode\Resources\Printer;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;
use Rawilk\Printing\Exceptions\InvalidArgument;

class ComputerService extends AbstractService
{
    /**
     * @param  null|array  $params
     *                              `limit` => the max number of rows that will be returned - default is 100
     *                              `dir` => `asc` for ascending, `desc` for descending - default is `desc`
     *                              `after` => retrieve records with an ID after the provided value
     * @return Collection<int, Computer>
     */
    public function all(?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        return $this->requestCollection('get', '/computers', $params, opts: $opts, expectedResource: Computer::class);
    }

    public function retrieve(int $id, ?array $params = null, null|array|RequestOptions $opts = null): ?Computer
    {
        $computers = $this->requestCollection('get', $this->buildPath('/computers/%s', $id), $params, opts: $opts, expectedResource: Computer::class);

        return $computers->first();
    }

    /**
     * Retrieve a specific set of computers.
     *
     * @param  array<int, int>  $ids  the IDs of the computers to retrieve
     * @param  null|array  $params
     *                              `limit` => the max number of rows that will be returned - default is 100
     *                              `dir` => `asc` for ascending, `desc` for descending - default is `desc`
     *                              `after` => retrieve records with an ID after the provided value
     * @return Collection<int, Computer>
     */
    public function retrieveSet(array $ids, ?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        throw_unless(
            filled($ids),
            InvalidArgument::class,
            'At least one computer ID must be provided for this request.',
        );

        return $this->requestCollection('get', $this->buildPath('/computers/%s', ...$ids), $params, opts: $opts, expectedResource: Computer::class);
    }

    /**
     * Delete a given computer. Returns an array of affected IDs.
     */
    public function delete(int $id, ?array $params = null, null|array|RequestOptions $opts = null): array
    {
        return $this->request('delete', $this->buildPath('/computers/%s', $id), $params, opts: $opts);
    }

    /**
     * Delete a set of computers. Omit or use an empty array of $ids to delete all computers.
     * Returns an array of affected IDs.
     */
    public function deleteMany(array $ids = [], ?array $params = null, null|array|RequestOptions $opts = null): array
    {
        $path = filled($ids)
            ? $this->buildPath('/computers/%s', ...$ids)
            : '/computers';

        return $this->request('delete', $path, $params, opts: $opts);
    }

    /**
     * Retrieve all printers attached to a given computer.
     *
     * @param  int|array  $parentId  the computer's ID; pass an array to retrieve printers for multiple computers
     * @param  null|array  $params
     *                              `limit` => the max number of rows that will be returned - default is 100
     *                              `dir` => `asc` for ascending, `desc` for descending - default is `desc`
     *                              `after` => retrieve records with an ID after the provided value
     * @return Collection<int, Printer>
     */
    public function printers(int|array $parentId, ?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        $path = is_array($parentId)
            ? $this->buildPath('/computers/%s/printers', ...$parentId)
            : $this->buildPath('/computers/%s/printers', $parentId);

        return $this->requestCollection('get', $path, $params, opts: $opts, expectedResource: Printer::class);
    }

    /**
     * Retrieve a set of printers attached to a given computer.
     *
     * @param  array|int  $parentId  the computer's ID; pass an array to retrieve a printer for multiple computers
     * @param  array|int  $printerId  the printer's ID; pass an array to retrieve a set of printers
     * @param  null|array  $params
     *                              `limit` => the max number of rows that will be returned - default is 100
     *                              `dir` => `asc` for ascending, `desc` for descending - default is `desc`
     *                              `after` => retrieve records with an ID after the provided value
     * @return Collection<int, Printer>|Printer|null
     */
    public function printer(int|array $parentId, int|array $printerId, ?array $params = null, null|array|RequestOptions $opts = null): Collection|Printer|null
    {
        $computerPath = is_array($parentId)
            ? $this->buildPath('/computers/%s', ...$parentId)
            : $this->buildPath('/computers/%s', $parentId);

        $printerPath = is_array($printerId)
            ? $this->buildPath('/printers/%s', ...$printerId)
            : $this->buildPath('/printers/%s', $printerId);

        $response = $this->requestCollection('get', $computerPath . $printerPath, $params, opts: $opts, expectedResource: Printer::class);

        return is_array($printerId) ? $response : $response->first();
    }
}
