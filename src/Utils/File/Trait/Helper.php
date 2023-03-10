<?php

namespace MyCustom\Utils\File\Trait;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * ファイル操作に関するヘルパーtrait
 */
trait Helper
{
    final public function fileUpload(UploadedFile $file, string $dirName = null, string $baseName = null): string|false
    {
        if (is_null($dirName)) $dirName = $this->dirName;
        if (is_null($baseName)) $baseName = $this->baseName;
        return $file->storeAs($dirName, $baseName);
    }
    final public function download(string $filePath = null): StreamedResponse
    {
        if (is_null($filePath)) $filePath = $this->filePath;
        return Storage::download($filePath);
    }
    final public function delete(string $filePath = null): bool
    {
        if (is_null($filePath)) $filePath = $this->filePath;
        return Storage::delete($filePath);
    }
    final public function isExist(string $filePath = null): bool
    {
        if (is_null($filePath)) $filePath = $this->filePath;
        return Storage::exists($filePath);
    }
    final public function mimeType(string $filePath = null): string
    {
        if (is_null($filePath)) $filePath = $this->filePath;
        return Storage::mimeType($filePath);
    }
    final public function size(string $filePath = null): string
    {
        if (is_null($filePath)) $filePath = $this->filePath;
        return Storage::size($filePath);
    }
}
