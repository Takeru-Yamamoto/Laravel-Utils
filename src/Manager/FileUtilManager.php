<?php

namespace MyCustom\Utils\Manager;

use MyCustom\Utils\File\StorageFile;
use MyCustom\Utils\File\StorageFileUtil;
use MyCustom\Utils\File\RequestFile;
use MyCustom\Utils\File\RequestFileUtil;
use MyCustom\Utils\File\Trait;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FileUtilManager
{
    use Trait\Exceptions,
        Trait\FileType,
        Trait\Helper;

    public function storage(string $dirName, string $baseName): StorageFileUtil
    {
        $mimeType = $this->mimeType($dirName . "/" . $baseName);

        $storageFile = match (true) {
            $this->isImageFile($mimeType) => new StorageFile\ImageStorageFile($dirName, $baseName),
            $this->isVideoFile($mimeType) => new StorageFile\VideoStorageFile($dirName, $baseName),
            $this->isTextFile($mimeType)  => new StorageFile\TextStorageFile($dirName, $baseName),
            $this->isExcelFile($mimeType) => new StorageFile\ExcelStorageFile($dirName, $baseName),

            default                       => null
        };

        if (!$storageFile instanceof StorageFileUtil) throw $this->StorageFileNotSupportedException($dirName . "/" . $baseName, $mimeType);

        return $storageFile;
    }
    public function request(Request $request, string $postName, string $dirName = ""): RequestFileUtil|array
    {
        $files = $request->file($postName);
        if (is_null($files)) throw $this->RequestFileNotFoundException($postName);

        if ($files instanceof UploadedFile) return $this->makeRequestFile($files, $dirName);

        if (is_array($files)) {
            $requestFiles = [];

            foreach ($files as $file) {
                $requestFiles[] = $this->makeRequestFile($file, $dirName);
            }
        }

        return $requestFiles;
    }
    private function makeRequestFile(UploadedFile $file, string $dirName): RequestFileUtil
    {
        $requestFile = match (true) {
            $this->isImageFile($file->getClientMimeType()) => new RequestFile\ImageRequestFile($file, $dirName),
            $this->isVideoFile($file->getClientMimeType()) => new RequestFile\VideoRequestFile($file, $dirName),
            $this->isTextFile($file->getClientMimeType())  => new RequestFile\TextRequestFile($file, $dirName),
            $this->isExcelFile($file->getClientMimeType()) => new RequestFile\ExcelRequestFile($file, $dirName),

            default                                        => null
        };

        if (!$requestFile instanceof RequestFileUtil) throw $this->RequestFileNotSupportedException($file->getClientOriginalName(), $file->getClientMimeType());

        return $requestFile;
    }

    public function createExcel(string $baseName, string $dirName = null): StorageFile\ExcelStorageFile
    {
        if (str_contains($baseName, ".xlsx") === false) $baseName .= ".xlsx";
        $sheet = new Spreadsheet();
        $writer = new Xlsx($sheet);
        $filePath = is_null($dirName) ? $baseName : $dirName . "/" . $baseName;
        $writer->save($filePath);
        return new StorageFile\ExcelStorageFile($dirName, $baseName);
    }

    public function createCsv(string $baseName, string $dirName = null): StorageFile\TextStorageFile
    {
        if (str_contains($baseName, ".csv") === false) $baseName .= ".csv";
        $sheet = new Spreadsheet();
        $writer = new Csv($sheet);
        $filePath = is_null($dirName) ? $baseName : $dirName . "/" . $baseName;
        $writer->save($filePath);
        return new StorageFile\TextStorageFile($dirName, $baseName);
    }
}
