<?php

namespace MyCustom\Utils\File\Exception;

class RequestFileNotSupportedException extends \Exception
{
    public function __construct(string $baseName, string $mimeType)
    {
        parent::__construct("このファイルがサポートされているリクエストファイルがありません。 該当ファイル名: " . $baseName . " MIMEタイプ: " . $mimeType);
    }
}
