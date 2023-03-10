<?php

namespace MyCustom\Utils\File\StorageFile;

use MyCustom\Utils\File\StorageFileUtil;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv as CSVReader;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final class TextStorageFile extends StorageFileUtil
{
    public Spreadsheet $file;

    private Worksheet $targetSheet;

    private string $characterCode;
    private array $data;

    private array $characterCodes = [
        'UTF-8',
        'eucJP-win',
        'SJIS-win',
        'ASCII',
        'EUC-JP',
        'SJIS',
        'JIS',
    ];

    function __construct(string $dirName, string $baseName)
    {
        parent::__construct($dirName, $baseName);

        $this->setChild();
    }
    function __clone()
    {
        parent::__clone();

        $this->file          = clone $this->file;

        $this->targetSheet   = clone $this->targetSheet;
        $this->characterCode = clone $this->characterCode;
        $this->data          = clone $this->data;
    }
    public function params(): array
    {
        $params = [
            "targetSheet"   => $this->targetSheet,
            "characterCode" => $this->characterCode,
            "data"          => $this->data,
        ];

        return array_merge($params, parent::params());
    }

    public function save(string $dirName, string $baseName, string $format = null): self
    {
        if ($format === "xlsx") {
            if (!str_contains($baseName, ".xlsx")) $baseName .= ".xlsx";
            $writer = new Xlsx($this->file);
        } else {
            if (!str_contains($baseName, ".csv")) $baseName .= ".csv";
            $writer = new Csv($this->file);
        }

        $writer->save($dirName . "/" . $baseName);
        $this->reset($dirName, $baseName);
        return $this;
    }
    protected function setChild(): void
    {
        $csv = new CSVReader;

        $this->characterCode = $this->checkCharacterCode();
        $this->file          = $csv->setInputEncoding($this->characterCode)->load(str_replace("public", "storage", $this->filePath));
        $this->targetSheet   = $this->file->getSheet(0);
        $this->data          = $this->targetSheet->rangeToArray($this->targetSheet->calculateWorksheetDimension());
    }

    public function characterCode(): string
    {
        return $this->characterCode;
    }
    public function targetSheet(): Worksheet
    {
        return $this->targetSheet;
    }
    public function data(): array
    {
        return $this->data;
    }

    public function cellValue(string $cell): mixed
    {
        return $this->targetSheet->getCell($cell)->getValue();
    }
    public function setCellValue(string $cell, mixed $value): self
    {
        $this->targetSheet->setCellValue($cell, $value);
        return $this;
    }
    public function cellValues(string $range = null): array
    {
        if (is_null($range)) $range = $this->targetSheet->calculateWorksheetDimension();
        return $this->targetSheet->rangeToArray($range, null, true, true, false);
    }
    public function setCellValues(array $values, string $startCell = null): self
    {
        $this->targetSheet->fromArray($values, null, $startCell, false);
        return $this;
    }
    public function cellIsNull(string $cell): bool
    {
        return is_null($this->cellValue($cell));
    }
    public function mergeCells(string $range): self
    {
        $this->targetSheet->mergeCells($range);
        return $this;
    }

    public function rowHeight(int $row): float
    {
        return $this->targetSheet->getRowDimension($row)->getRowHeight();
    }
    public function setRowHeight(int $row, float $height): self
    {
        $this->targetSheet->getRowDimension($row)->setRowHeight($height);
        return $this;
    }
    public function columnWidth(int $column): float
    {
        return $this->targetSheet->getColumnDimension($column)->getWidth();
    }
    public function setColumnWidth(int $column, float $width): self
    {
        $this->targetSheet->getColumnDimension($column)->setWidth($width);
        return $this;
    }

    private function checkCharacterCode(): string
    {
        $contents = file_get_contents($this->filePath);

        foreach ($this->characterCodes as $characterCode) {
            if ($contents === mb_convert_encoding($contents, $characterCode, $characterCode)) {
                return $characterCode;
            }
        }

        throw $this->CharacterCodeNotFoundException();
    }
}
