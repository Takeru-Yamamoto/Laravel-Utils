<?php

namespace MyCustom\Utils\File\Exception;

class StorageFileNotSupportedException extends \Exception
{
    public function __construct(string $filePath, string $mimeType)
    {
        parent::__construct("このファイルがサポートされているストレージファイルがありません。 ファイルパス: " . $filePath . " MIMEタイプ: " . $mimeType);
    }
}
