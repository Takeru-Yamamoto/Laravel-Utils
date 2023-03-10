<?php

namespace MyCustom\Utils\File\StorageFile;

use MyCustom\Utils\File\StorageFileUtil;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final class ExcelStorageFile extends StorageFileUtil
{
    public Spreadsheet $file;

    private int $sheetCount;

    private array $sheets;
    private array $sheetData;

    private Worksheet $targetSheet;
    private int $targetSheetPage;

    function __construct(string $dirName, string $baseName)
    {
        parent::__construct($dirName, $baseName);

        $this->setChild();
    }
    function __clone()
    {
        parent::__clone();

        $this->file            = clone $this->file;

        $this->sheetCount      = clone $this->sheetCount;

        $this->sheets          = clone $this->sheets;
        $this->sheetData       = clone $this->sheetData;

        $this->targetSheet     = clone $this->targetSheet;
        $this->targetSheetPage = clone $this->targetSheetPage;
    }

    public function save(string $dirName, string $baseName, string $format = null): self
    {
        if ($format === "csv") {
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
        $this->file = IOFactory::load(str_replace("public", "storage", $this->filePath));

        $this->sheetCount = $this->file->getSheetCount();

        $this->pivotFirst();

        $this->sheets    = [];
        $this->sheetData = [];

        for ($i = 0; $i < $this->sheetCount; $i++) {
            $sheet = $this->file->getSheet($i);

            $this->sheets[]    = $sheet;
            $this->sheetData[] = [
                "page"    => $i,
                "name"    => $sheet->getTitle(),
                "rows"    => $sheet->getHighestRow(),
                "columns" => $sheet->getHighestRow(),
                "range"   => $sheet->calculateWorksheetDimension(),
            ];
        }
    }

    public function params(): array
    {
        $params = [
            "sheets"          => $this->sheets(),
            "sheetCount"      => $this->sheetCount(),
            "sheetData"       => $this->sheetData(),
            "targetSheet"     => $this->targetSheet(),
            "targetSheetPage" => $this->targetSheetPage(),
        ];

        return array_merge($params, parent::params());
    }

    public function sheets(): array
    {
        return $this->sheets;
    }
    public function sheetCount(): int
    {
        return $this->sheetCount;
    }
    public function sheetData(): array
    {
        return $this->sheetData;
    }
    public function targetSheet(): Worksheet
    {
        return $this->targetSheet;
    }
    public function targetSheetPage(): int
    {
        return $this->targetSheetPage;
    }

    public function selectSheet(int $page = null): self
    {
        if (is_null($page)) $page = $this->targetSheetPage;
        if ($page >= 0 && $page <= $this->sheetCount) $this->targetSheet = $this->file->getSheet($page);
        return $this;
    }
    public function pivotPrev(): self
    {
        if ($this->targetSheetPage !== 0) $this->targetSheetPage--;
        return $this->selectSheet();
    }
    public function pivotNext(): self
    {
        if ($this->targetSheetPage < $this->sheetCount) $this->targetSheetPage++;
        return $this->selectSheet();
    }
    public function pivotFirst(): self
    {
        $this->targetSheetPage = 0;
        return $this->selectSheet();
    }
    public function pivotLast(): self
    {
        $this->targetSheetPage = $this->sheetCount;
        return $this->selectSheet();
    }
    public function addSheet(string $sheetName): self
    {
        $sheet = new Worksheet($this->file, $sheetName);
        $this->file->addSheet($sheet);
        return $this;
    }
    public function sheetTitle(): string
    {
        return $this->targetSheet->getTitle();
    }
    public function setSheetTitle(string $title): self
    {
        $this->targetSheet->setTitle($title);
        return $this;
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
}
