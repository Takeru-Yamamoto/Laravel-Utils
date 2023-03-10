<?php

namespace MyCustom\Utils\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static HttpUtil make(string $url, array $params = [])
 * 
 * @see \MyCustom\Utils\Http\Manager
 */
class HttpUtil extends Facade
{
    /** 
     * Get the registered name of the component. 
     * 
     * @return string 
     */
    protected static function getFacadeAccessor()
    {
        return "HttpUtil";
    }
}
