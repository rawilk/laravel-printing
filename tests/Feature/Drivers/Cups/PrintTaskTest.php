<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Drivers\Cups\Cups;
use Rawilk\Printing\Exceptions\PrintTaskFailed;

beforeEach(function () {
    Http::preventStrayRequests();

    $this->driver = new Cups;
});

test('printer uri is required', function () {
    $this->driver
        ->newPrintTask()
        ->content('foo')
        ->send();
})->throws(PrintTaskFailed::class, 'A printer must be specified');

test('content is required', function () {
    $this->driver
        ->newPrintTask()
        ->printer('/foo')
        ->send();
})->throws(PrintTaskFailed::class, 'No content was provided');
