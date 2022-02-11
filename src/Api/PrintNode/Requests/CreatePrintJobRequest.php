<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;
use Rawilk\Printing\Exceptions\PrintTaskFailed;

class CreatePrintJobRequest extends PrintNodeRequest
{
    public function send(PrintJob $job): PrintJob
    {
        $data = array_filter([
            'contentType' => $job->contentType,
            'content' => $job->content,
            'printer' => $job->printerId,
            'title' => $job->title,
            'source' => $job->source,
            'options' => $job->options,
        ], fn ($value) => ! is_null($value) && $value !== '');

        $jobId = $this->postRequest('printjobs', $data);

        if (! $jobId) {
            throw PrintTaskFailed::noJobCreated();
        }

        return (new PrintJobRequest($this->apiKey))->response($jobId);
    }
}
