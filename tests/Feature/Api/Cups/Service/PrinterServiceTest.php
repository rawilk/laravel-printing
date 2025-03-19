<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\CupsClient;
use Rawilk\Printing\Api\Cups\Exceptions\CupsRequestFailed;
use Rawilk\Printing\Api\Cups\Service\PrinterService;

// Sending real api calls here
beforeEach(function () {
    $client = new CupsClient;
    $this->service = new PrinterService($client);
});

it('retrieves all printers', function () {
    $printers = $this->service->all();

    expect($printers)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    if ($printers->count()) {
        expect($printers->first())->toBeInstanceOf(\Rawilk\Printing\Api\Cups\Resources\Printer::class);
    }
});

it('retrieves printer by id (url)', function () {
    $printers = $this->service->all();

    if ($printers->count()) {
        $printer = $this->service->retrieve($printers[0]->uri);
        expect($printer)->toBeInstanceOf(\Rawilk\Printing\Api\Cups\Resources\Printer::class);
    }
    expect(true)->toBeTrue();
});

it('retrieves a non existing printer by id (url)', function () {
    $config = $this->service->getClient()->getConfig();
    $schema = $config['secure'] ? 'https' : 'http';
    $this->service->retrieve("{$schema}://{$config['ip']}:{$config['port']}/John_doe_123555465");
})->throws(CupsRequestFailed::class);

it('can retrieve printer\'s printjobs', function () {
    $printers = $this->service->all();
    if ($printers->count()) {
        expect($this->service->printJobs($printers->first()->uri))->toBeInstanceOf(\Illuminate\Support\Collection::class);
    }
    expect(true)->toBeTrue();
});
