<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Resources;

use Rawilk\Printing\Api\Cups\CupsObject;
use Rawilk\Printing\Api\Cups\Enums\JobState;
use Rawilk\Printing\Api\Cups\Enums\OperationAttribute;

/**
 * A `PrintJob` represents a job sent to a CUPS printer.
 *
 * @property-read string $uri The uri to the job. Alias to `$jobUri`
 * @property-read string $jobUri The uri to the job.
 * @property-read null|string $jobName The name of the job.
 * @property-read string $jobPrinterUri The uri to the job the printer was sent to.
 * @property-read int $jobState An integer representation of the job's state.
 * @property-read null|string $dateTimeAtCreation The date/time the job was sent to the printer.
 */
class PrintJob extends CupsObject
{
    public static function defaultRequestedAttributes(): array
    {
        return [
            OperationAttribute::JobUri->toKeyword(),
            OperationAttribute::JobState->toKeyword(),
            OperationAttribute::NumberOfDocuments->toKeyword(),
            OperationAttribute::JobName->toKeyword(),
            OperationAttribute::DocumentFormat->toKeyword(),
            OperationAttribute::DateTimeAtCreation->toKeyword(),
            OperationAttribute::JobPrinterStateMessage->toKeyword(),
            OperationAttribute::JobPrinterUri->toKeyword(),
        ];
    }

    public function state(): ?JobState
    {
        return JobState::tryFrom($this->jobState);
    }

    public function printerName(): ?string
    {
        // Attempt to extract the printer's name from the uri.
        if (preg_match('/printers\/(.*)$/', $this->jobPrinterUri, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function mutateAttributes(array $values): array
    {
        $values['job-uri'] = $this->attributeValue($values, 'job-uri');
        $values['job-name'] = $this->attributeValue($values, 'job-name');
        $values['job-printer-uri'] = $this->attributeValue($values, 'job-printer-uri');
        $values['job-state'] = $this->attributeValue($values, 'job-state', JobState::Pending->value);

        return $values;
    }
}
