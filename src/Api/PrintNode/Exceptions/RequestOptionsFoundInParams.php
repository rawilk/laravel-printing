<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Exceptions;

use InvalidArgumentException;
use Rawilk\Printing\Exceptions\ExceptionInterface;

class RequestOptionsFoundInParams extends InvalidArgumentException implements ExceptionInterface
{
    public static function make(array $optionKeys): static
    {
        $message = sprintf(
            <<<'TXT'
            Options found in $params: %s. Options should be passed in their own
            array after $params. (HINT: pass an empty array to $params if you do
            not have any.)
            TXT,
            implode(', ', $optionKeys),
        );

        return new static($message);
    }
}
