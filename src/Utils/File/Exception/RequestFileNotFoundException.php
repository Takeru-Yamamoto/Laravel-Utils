<?php

namespace MyCustom\Utils\File\Exception;

class RequestFileNotFoundException extends \Exception
{
    public function __construct(string $postName)
    {
        parent::__construct("アップロードされたファイルがありません。 該当ファイル名: " . $postName);
    }
}
