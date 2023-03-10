<?php

namespace MyCustom\Utils\File;

use MyCustom\Utils\BaseUtil;
use MyCustom\Utils\File\Trait;

abstract class FileUtil extends BaseUtil
{
    use Trait\Exceptions,
        Trait\FileType,
        Trait\Helper;

    protected string $dirName;
    protected string $baseName;

    protected string $filePath;
    protected string $storagePath;

    protected string $mimeType;
    protected int $size;

    protected string $extension;
    protected string $fileName;

    protected function setFileUtil(
        string $dirName,
        string $baseName,
        string $mimeType = null,
        int $size = null,
        string $extension = null,
    ): void {
        if (!str_contains($dirName, "publlic")) $dirName = empty($dirName) || $dirName === "/" ? "public" : "public/" . $dirName;

        $this->dirName  = $dirName;
        $this->baseName = $baseName;

        $this->filePath    = $this->dirName . "/" . $this->baseName;
        $this->storagePath = storage_path("app/" . $this->filePath);

        $this->mimeType = is_null($mimeType) ? $this->mimeType() : $mimeType;
        $this->size     = is_null($size) ? $this->size() : $size;

        if (is_null($extension)) {
            $exploded        = explode(".", $baseName);
            $this->extension = end($exploded);
        } else {
            $this->extension = $extension;
        }

        $this->fileName = str_replace("." . $this->extension, "", $baseName);
    }
    function __clone()
    {
        $this->dirName     = clone $this->dirName;
        $this->baseName    = clone $this->baseName;
        $this->filePath    = clone $this->filePath;
        $this->storagePath = clone $this->storagePath;
        $this->mimeType    = clone $this->mimeType;
        $this->size        = clone $this->size;
        $this->extension   = clone $this->extension;
        $this->fileName    = clone $this->fileName;
    }
    public function params(): array
    {
        return [
            "dirName"     => $this->dirName,
            "baseName"    => $this->baseName,
            "fileName"    => $this->fileName,
            "extension"   => $this->extension,
            "mimeType"    => $this->mimeType,
            "size"        => $this->size,
            "filePath"    => $this->filePath,
            "storagePath" => $this->filePath,
        ];
    }
}
