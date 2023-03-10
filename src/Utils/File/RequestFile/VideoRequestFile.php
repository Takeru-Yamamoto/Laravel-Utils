<?php

namespace MyCustom\Utils\File\RequestFile;

use MyCustom\Utils\File\RequestFileUtil;

use Illuminate\Http\UploadedFile;

final class VideoRequestFile extends RequestFileUtil
{
    function __construct(UploadedFile $file, string $dirName)
    {
        parent::__construct($file, $dirName);
    }
}
