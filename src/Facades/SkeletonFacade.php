<?php

namespace Rawilk\Facades\Skeleton;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Rawilk\Skeleton\Skeleton
 */
class SkeletonFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'skeleton';
    }
}
