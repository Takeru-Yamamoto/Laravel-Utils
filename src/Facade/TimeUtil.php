<?php

namespace MyCustom\Utils\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static TimeUtil make(string $method)
 * 
 * @see \MyCustom\Utils\Time\Manager
 */
class TimeUtil extends Facade
{
    /** 
     * Get the registered name of the component. 
     * 
     * @return string 
     */
    protected static function getFacadeAccessor()
    {
        return "TimeUtil";
    }
}
