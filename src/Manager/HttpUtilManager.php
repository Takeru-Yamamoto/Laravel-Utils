<?php

namespace MyCustom\Utils\Manager;

use MyCustom\Utils\Http\HttpUtil;

class HttpUtilManager
{
    public function make(string $url, array $params = []): HttpUtil
    {
        return new HttpUtil($url, $params);
    }
}
