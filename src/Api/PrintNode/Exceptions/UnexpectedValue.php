<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Exceptions;

use Rawilk\Printing\Exceptions\ExceptionInterface;
use UnexpectedValueException;

class UnexpectedValue extends UnexpectedValueException implements ExceptionInterface
{
}
