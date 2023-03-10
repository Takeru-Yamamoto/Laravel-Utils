<?php

namespace MyCustom\Utils\Manager;

use MyCustom\Utils\Date\DateUtil;

class DateUtilManager
{
    public function make(string|null $date = null): DateUtil
    {
        return new DateUtil($date);
    }
}
