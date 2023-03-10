<?php

namespace MyCustom\Utils\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \MyCustom\Utils\File\StorageFileUtil storage(string $dirName, string $baseName)
 * @method static \MyCustom\Utils\File\RequestFileUtil|array request(Request $request, string $postName, string $dirName = "")
 * @method static \MyCustom\Utils\File\StorageFile\ExcelStorageFile createExcel(string $baseName, string $dirName = null)
 * @method static \MyCustom\Utils\File\StorageFile\TextStorageFile createCsv(string $baseName, string $dirName = null)
 * 
 * @see \MyCustom\Utils\File\Manager
 */
class FileUtil extends Facade
{
    /** 
     * Get the registered name of the component. 
     * 
     * @return string 
     */
    protected static function getFacadeAccessor()
    {
        return "FileUtil";
    }
}
