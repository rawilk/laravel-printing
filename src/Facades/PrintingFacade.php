<?php

namespace Rawilk\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Rawilk\Printing\Printing
 */
class PrintingFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'printing';
    }
}
