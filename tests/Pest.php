<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Cups;
use Rawilk\Printing\Api\Cups\Enums as CupsEnum;
use Rawilk\Printing\Api\Cups\Types as CupsType;
use Rawilk\Printing\Api\PrintNode\PrintNode;
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

uses()->afterEach(function () {
    Cups::reset();
})->in(
    'Feature/Drivers/Cups',
    'Feature/Api/Cups',
);

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

function baseCupsJobData(): array
{
    return [
        'uri' => 'localhost:631/jobs/123',
        'job-uri' => new CupsType\Uri('localhost:631/jobs/123'),
        'job-printer-uri' => new CupsType\Uri('localhost:631/printers/TestPrinter'),
        'job-name' => new CupsType\TextWithoutLanguage('my print job'),
        'job-state' => new CupsType\Primitive\Enum(CupsEnum\JobState::Completed->value),
    ];
}

function baseCupsPrinterData(): array
{
    return [
        'uri' => 'localhost:631',
        'printer-uri-supported' => new CupsType\TextWithoutLanguage('localhost:631'),
        'printer-name' => new CupsType\TextWithoutLanguage('TestPrinter'),
        'printer-state' => new CupsType\Primitive\Enum(CupsEnum\PrinterState::Idle->value),
    ];
}
