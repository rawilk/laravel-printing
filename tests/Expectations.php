<?php

declare(strict_types=1);

use Carbon\CarbonInterface;

// Add better handling for 'toBe' for Dates.
expect()->intercept('toBe', CarbonInterface::class, function (CarbonInterface $date) {
    return expect($date->equalTo($this->value))->toBeTrue(
        "Expected date [{$date}] does not equal actual date [{$this->value}]",
    );
});
