<?php

namespace MyCustom\Utils\Manager;

use MyCustom\Utils\Time\TimeUtil;

class TimeUtilManager
{
    public function make(string $method): TimeUtil
    {
        return new TimeUtil($method);
    }
}
