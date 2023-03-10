<?php

namespace MyCustom\Utils\File\Trait;

/**
 * ファイルの種類に関するtrait
 */
trait FileType
{
    final public function isImageFile(string $mimeType = null): bool
    {
        if (is_null($mimeType)) $mimeType = $this->mimeType;
        return str_contains($mimeType, 'image');
    }
    final public function isVideoFile(string $mimeType = null): bool
    {
        if (is_null($mimeType)) $mimeType = $this->mimeType;
        return str_contains($mimeType, 'video');
    }
    final public function isTextFile(string $mimeType = null): bool
    {
        if (is_null($mimeType)) $mimeType = $this->mimeType;
        return str_contains($mimeType, 'text');
    }
    final public function isExcelFile(string $mimeType = null): bool
    {
        if (is_null($mimeType)) $mimeType = $this->mimeType;
        return str_contains($mimeType, 'vnd.ms-excel') || str_contains($mimeType, 'vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
