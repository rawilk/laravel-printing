<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\PrintNode;
use Rawilk\Printing\Drivers\Cups\Entity\Printer;
use Rawilk\Printing\Drivers\Cups\Entity\PrintJob;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class)->in(
    'Unit',
    'Feature',
);

uses()->afterEach(function () {
    PrintNode::setApiKey(null);
})->in(
    'Feature/Drivers/PrintNode',
    'Feature/Api/PrintNode',
);

// uses(TestCase::class)->in('Feature/FactoryTest.php');
// uses(TestCase::class)->in('Feature/PrintingTest.php');
// uses(TestCase::class)->in('Feature/Receipts');
// uses(TestCase::class)->in('Feature/Api/PrintNode/Entity');
// uses(PrintNodeTestCase::class)->in('Feature/Api/PrintNode/Requests');
// uses(TestCase::class)->in('Feature/Drivers');

// Helpers
function samplePrintNodeData(string $file): array
{
    return json_decode(
        file_get_contents(__DIR__ . "/Feature/Api/PrintNode/Fixtures/responses/{$file}.json"),
        true,
        512,
        JSON_THROW_ON_ERROR,
    );
}

function createCupsJob(): Rawilk\Printing\Drivers\Cups\Entity\PrintJob
{
    $cupsJob = new PrintJob([
        'job-uri' => new Rawilk\Printing\Api\Cups\Types\Uri('localhost:631/jobs/123'),
        'job-printer-uri' => new Rawilk\Printing\Api\Cups\Types\Uri('localhost:631/printers/printer-name'),
        'job-name' => new Rawilk\Printing\Api\Cups\Types\TextWithoutLanguage('my print job'),
        'job-state' => new Rawilk\Printing\Api\Cups\Types\Primitive\Enum(Rawilk\Printing\Drivers\Cups\Enum\JobState::COMPLETED->value),
    ]);

    return $cupsJob;
}

function createCupsPrinter(array $attributes = []): Rawilk\Printing\Drivers\Cups\Entity\Printer
{
    $cupsPrinter = new Printer([
        'printer-name' => new Rawilk\Printing\Api\Cups\Types\TextWithoutLanguage('printer-name'),
        'printer-state' => new Rawilk\Printing\Api\Cups\Types\Primitive\Enum(Rawilk\Printing\Drivers\Cups\Enum\PrinterState::IDLE->value),
        'printer-uri-supported' => new Rawilk\Printing\Api\Cups\Types\TextWithoutLanguage('localhost:631'),
        ...$attributes,
    ]);

    return $cupsPrinter;
}
