<?php

namespace MyCustom\Utils\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static DateUtil make(string|null $date = null)
 * 
 * @see \MyCustom\Utils\Date\Manager
 */
class DateUtil extends Facade
{
    /** 
     * Get the registered name of the component. 
     * 
     * @return string 
     */
    protected static function getFacadeAccessor()
    {
        return "DateUtil";
    }
}
