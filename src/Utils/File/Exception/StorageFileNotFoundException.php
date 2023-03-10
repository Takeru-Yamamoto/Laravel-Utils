<?php

namespace MyCustom\Utils\File\Exception;

class StorageFileNotFoundException extends \Exception
{
    public function __construct(string $filePath)
    {
        parent::__construct("入力されたPathのファイルがありません。 ファイルパス: " . $filePath);
    }
}
