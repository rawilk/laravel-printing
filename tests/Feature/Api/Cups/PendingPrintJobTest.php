<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\PendingPrintJob;
use Rawilk\Printing\Api\Cups\PendingRequest;
use Rawilk\Printing\Api\Cups\Resources\Printer as PrinterResource;
use Rawilk\Printing\Drivers\Cups\Entity\Printer as DriverPrinter;
use Rawilk\Printing\Exceptions\InvalidArgument;

beforeEach(function () {
    $this->job = new PendingPrintJob;
});

it('throws when an invalid content type is set', function () {
    $this->job->setContentType('foo');
})->throws(InvalidArgument::class, 'Invalid content type "foo".');

test('set printer', function (mixed $printer) {
    $this->job->setPrinter($printer);

    expect($this->job->printerUri)->toBe('/foo');
})->with([
    'string' => '/foo',
    'api resource' => fn () => new PrinterResource('/foo'),
    'driver' => fn () => new DriverPrinter(new PrinterResource('/foo')),
]);

it('generates a pending request object', function () {
    $this->job->setPrinter('/foo');

    expect($this->job->toPendingRequest())->toBeInstanceOf(PendingRequest::class);
});
