<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Enums\AuthenticationType;
use Rawilk\Printing\Api\PrintNode\Enums\ContentType;
use Rawilk\Printing\Api\PrintNode\Enums\PrintJobOption;
use Rawilk\Printing\Api\PrintNode\PendingPrintJob;
use Rawilk\Printing\Api\PrintNode\Resources\Printer as PrinterResource;
use Rawilk\Printing\Drivers\PrintNode\Entity\Printer as DriverPrinter;
use Rawilk\Printing\Exceptions\InvalidArgument;
use Rawilk\Printing\Exceptions\InvalidOption;

beforeEach(function () {
    $this->job = new PendingPrintJob;
});

it('throws when an unsupported content type is set', function () {
    $this->job->setContentType('foo');
})->throws(InvalidArgument::class, 'Invalid content type "foo".');

test('set printer', function (mixed $printer) {
    $this->job->setPrinter($printer);

    expect($this->job->printerId)->toBe(1);
})->with([
    'int' => 1,
    'api resource' => fn () => new PrinterResource(1),
    'driver' => fn () => new DriverPrinter(new PrinterResource(1)),
]);

it('can generate a payload to send to PrintNode', function () {
    $this->job->setPrinter(1)->setContent('My content');

    expect($this->job->toArray())->toEqualCanonicalizing([
        'printerId' => 1,
        'contentType' => ContentType::RawBase64->value,
        'content' => base64_encode('My content'),
    ]);
});

it('can send options to PrintNode', function () {
    $this->job->setOptions([
        PrintJobOption::Rotate->value => 90,
        PrintJobOption::Paper->value => 'Letter',
    ])->setPrinter(1)->setContent('My content');

    $data = $this->job->toArray();

    expect($data)->toHaveKey('options')
        ->and($data['options'])->toEqualCanonicalizing([
            PrintJobOption::Rotate->value => 90,
            PrintJobOption::Paper->value => 'Letter',
        ]);
});

it('verifies options when generating data to send to PrintNode', function () {
    $this->job->setOptions([
        'foo' => 'bar',
    ])->setPrinter(1)->setContent('My content');

    $this->job->toArray();
})->throws(InvalidOption::class, 'The provided option key "foo" is not valid for a PrintNode request.');

it('can use authentication for some content types', function (ContentType $type) {
    $this->job->setContentType($type)->setPrinter(1)->setContent('My content');

    $this->job->setAuth('foo', 'bar');

    $data = $this->job->toArray();

    expect($data)->toHaveKey('authentication')
        ->and($data['authentication'])->toEqualCanonicalizing([
            'type' => AuthenticationType::Basic->value,
            'credentials' => [
                'user' => 'foo',
                'pass' => 'bar',
            ],
        ]);
})->with([
    ContentType::RawUri,
    ContentType::PdfUri,
]);

describe('options', function () {
    it('throws for an unexpected option key', function () {
        $this->job->setOption('foo', 'bar');

        $this->job->verifyOptions();
    })->throws(InvalidOption::class, 'The provided option key "foo" is not valid for a PrintNode request.');

    test('string options must be a string', function (PrintJobOption $option) {
        $this->job->setOption($option, 1);

        $this->job->verifyOptions();
    })->with([
        PrintJobOption::Bin,
        PrintJobOption::Dpi,
        PrintJobOption::Duplex,
        PrintJobOption::Media,
        PrintJobOption::Pages,
        PrintJobOption::Paper,
    ])->throws(InvalidOption::class, 'must be a string');

    test('boolean options must be a boolean value', function (PrintJobOption $option) {
        $this->job->setOption($option, 'true');

        $this->job->verifyOptions();
    })->with([
        PrintJobOption::Collate,
        PrintJobOption::Color,
        PrintJobOption::FitToPage,
    ])->throws(InvalidOption::class, 'must be a boolean value');

    test('integer options must be an integer', function (PrintJobOption $option) {
        $this->job->setOption($option, '1');

        $this->job->verifyOptions();
    })->with([
        PrintJobOption::Copies,
        PrintJobOption::Nup,
        PrintJobOption::Rotate,
    ])->throws(InvalidOption::class, 'must be an integer');

    test('copies must be at least 1', function () {
        $this->job->setOption(PrintJobOption::Copies, 0);

        $this->job->verifyOptions();
    })->throws(InvalidOption::class, 'The "copies" option must be at least 1');

    test('duplex must be a supported value', function () {
        $this->job->setOption(PrintJobOption::Duplex, 'foo');

        $this->job->verifyOptions();
    })->throws(InvalidOption::class, 'The "duplex" option value provided ("foo") is not supported. Must be one of: "long-edge", "short-edge", "one-sided"');

    test('rotate option must be a supported value', function () {
        $this->job->setOption(PrintJobOption::Rotate, 1);

        $this->job->verifyOptions();
    })->throws(InvalidOption::class, 'The provided value for the "rotate" option (1) is not valid. Must be one of: 0, 90, 180, 270');
});
