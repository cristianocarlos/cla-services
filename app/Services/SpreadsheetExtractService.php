<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SpreadsheetExtractService
{
    private function filePathFromUrl(string $fileUrl): string {
        $tempFilePath = tempnam(sys_get_temp_dir(), 'sheet_extract_');
        $fileContent = file_get_contents($fileUrl);
        file_put_contents($tempFilePath, $fileContent);
        return $tempFilePath;
    }

    public function getRows(Spreadsheet $spreadsheet): array {
        $sheet = $spreadsheet->getActiveSheet();
        $rows = [];
        foreach ($sheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = Date::isDateTime($cell)
                    ? Date::excelToDateTimeObject($cell->getValue())->format('Y-m-d')
                    : $cell->getValue();
            }
            if (empty(array_filter($rowData))) continue;
            $rows[] = $rowData;
        }
        return $rows;
    }

    private function csvLoad(string $filePath): Spreadsheet {
        $reader = new Csv;
        $reader->setDelimiter(';');
        return $reader->load($filePath);
    }

    private function sheetLoad(string $filePath, string $sheetName = ''): Spreadsheet {
        // $spreadsheet = IOFactory::load($filePath); // 4. Load the file using PhpSpreadsheet's automatic format detection
        $reader = IOFactory::createReaderForFile($filePath); // f you do not need cell styles, formatting, or font colors, tell the loader to ignore them. This cuts down memory usage significantly
        // $reader->setReadDataOnly(true); // Com isso não é possível trabalhar as datas
        if (!$sheetName) {
            $sheetNames = $reader->listWorksheetNames($filePath);
            $sheetName = $sheetNames[0]; // Carrega somente a primeira planilha
        }
        $reader->setLoadSheetsOnly($sheetName);
        return $reader->load($filePath); // 4. Load the file using PhpSpreadsheet's automatic format detection
    }

    public function getCsvRows(string $fileUrl): array {
        $filePath = $this->filePathFromUrl($fileUrl);
        $spreadsheet = $this->csvLoad($filePath);
        return $this->getRows($spreadsheet);
    }

    public function getSheetRows(string $fileUrl, string $sheetName = ''): array {
        $filePath = $this->filePathFromUrl($fileUrl);
        $spreadsheet = $this->sheetLoad($filePath, $sheetName);
        return $this->getRows($spreadsheet);
    }
}
