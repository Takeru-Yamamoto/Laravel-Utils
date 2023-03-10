<?php

namespace MyCustom\Utils\File\Trait;

use MyCustom\Utils\File\Exception;

/**
 * ファイルのExceptionに関するtrait
 */
trait Exceptions
{
    final public function RequestFileNotFoundException(string $postName): Exception\RequestFileNotFoundException
    {
        return new Exception\RequestFileNotFoundException($postName);
    }
    final public function RequestFileNotSupportedException(string $baseName, string $mimeType = null): Exception\RequestFileNotSupportedException
    {
        if (is_null($mimeType)) $mimeType = $this->mimeType;
        return new Exception\RequestFileNotSupportedException($baseName, $mimeType);
    }
    final public function StorageFileNotFoundException(string $filePath = null): Exception\StorageFileNotFoundException
    {
        if (is_null($filePath)) $filePath = $this->filePath;
        return new Exception\StorageFileNotFoundException($filePath);
    }
    final public function StorageFileNotSupportedException(string $filePath = null, string $mimeType = null): Exception\StorageFileNotSupportedException
    {
        if (is_null($filePath)) $filePath = $this->filePath;
        if (is_null($mimeType)) $mimeType = $this->mimeType;
        return new Exception\StorageFileNotSupportedException($filePath, $mimeType);
    }
    final public function CharacterCodeNotFoundException(string $filePath = null): Exception\CharacterCodeNotFoundException
    {
        if (is_null($filePath)) $filePath = $this->filePath;
        return new Exception\CharacterCodeNotFoundException($filePath);
    }
}
