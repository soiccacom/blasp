<?php

namespace Blaspsoft\Blasp;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Blaspsoft\Blasp\Skeleton\SkeletonClass
 */
class BlaspFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'blasp';
    }
}
