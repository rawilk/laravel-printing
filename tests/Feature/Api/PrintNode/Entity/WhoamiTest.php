<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Entity\Whoami;

test('can be created from array', function () {
    $whoami = new Whoami(samplePrintNodeData('whoami'));

    expect($whoami->id)->toBe(433);
    expect($whoami->firstName)->toEqual('Peter');
    expect($whoami->lastName)->toEqual('Tuthill');
    expect($whoami->email)->toEqual('peter@omlet.co.uk');
    expect($whoami->canCreateSubAccounts)->toBeFalse();
    expect($whoami->credits)->toBe(10134);
    expect($whoami->numComputers)->toBe(3);
    expect($whoami->totalPrints)->toBe(110);
    expect($whoami->tags)->toBeArray();
    expect($whoami->tags)->toBeEmpty();
    expect($whoami->permissions)->toBeArray();
    expect($whoami->permissions)->toEqual(['Unrestricted']);
    expect($whoami->state)->toEqual('active');
});

test('casts to array', function () {
    $data = samplePrintNodeData('whoami');
    $whoami = new Whoami($data);

    $asArray = $whoami->toArray();

    foreach ($data as $key => $value) {
        switch ($key) {
            case 'Tags':
                $key = 'tags';

                break;
            case 'firstname':
                $key = 'firstName';

                break;
            case 'lastname':
                $key = 'lastName';

                break;
        }

        $this->assertArrayHasKey($key, $asArray);
    }
});
