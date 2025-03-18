<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\Exceptions\PrintNodeApiRequestFailed;
use Rawilk\Printing\Api\PrintNode\PrintNodeClient;
use Rawilk\Printing\Api\PrintNode\Resources\Whoami;
use Rawilk\Printing\Api\PrintNode\Service\WhoamiService;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\FakesPrintNodeRequests;

uses(FakesPrintNodeRequests::class);

beforeEach(function () {
    $client = new PrintNodeClient(['api_key' => config('printing.drivers.printnode.key')]);
    $this->service = new WhoamiService($client);
});

describe('live api requests', function () {
    it('can hit the api successfully', function () {
        // We are sending an actual api request here!
        $response = $this->service->check();

        expect($response->id)->toEqual(env('PRINT_NODE_ID'));
    });
});

describe('fake api calls', function () {
    beforeEach(function () {
        Http::preventStrayRequests();

        $this->fakeRequests();
    });

    test('invalid api key does not work', function () {
        $this->fakeRequest('whoami_bad_api_key', code: 401);

        $this->service->check();
    })->throws(PrintNodeApiRequestFailed::class, 'API Key not found');

    it('gets account info', function () {
        $this->fakeRequest('whoami');

        $response = $this->service->check();

        expect($response)
            ->toBeInstanceOf(Whoami::class)
            ->id->toBe(433)
            ->firstname->toBe('Peter')
            ->lastname->toBe('Tuthill')
            ->state->toBe('active')
            ->credits->toBe(10134);
    });
});
