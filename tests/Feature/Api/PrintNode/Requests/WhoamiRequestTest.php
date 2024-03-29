<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Requests\WhoamiRequest;
use Rawilk\Printing\Exceptions\PrintNodeApiRequestFailed;

test('gets account info', function () {
    $this->fakeRequest('whoami', 'whoami');

    $whoami = (new WhoamiRequest('1234'))->response();

    expect($whoami->id)->toBe(433);
    expect($whoami->firstName)->toEqual('Peter');
    expect($whoami->lastName)->toEqual('Tuthill');
    expect($whoami->state)->toEqual('active');
    expect($whoami->credits)->toBe(10134);
});

test('invalid api key does not work', function () {
    $this->fakeRequest('whoami', 'whoami_bad_api_key', 401);

    $this->expectException(PrintNodeApiRequestFailed::class);
    $this->expectExceptionCode(401);
    $this->expectExceptionMessage('API Key not found');

    (new WhoamiRequest('foo'))->response();
});

test('actual requests can be made', function () {
    // We are sending an actual api request here!
    $whoami = (new WhoamiRequest($this->apiKey))->response();

    expect($whoami->id)->toEqual(env('PRINT_NODE_ID'));
});
