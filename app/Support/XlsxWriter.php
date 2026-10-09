<?php

namespace App\Support;

use RuntimeException;

class XlsxWriter
{
    public static function make(array $headers, iterable $rows, string $sheetName = 'Export'): string
    {
        $sheetRows = [$headers];
        foreach ($rows as $row) {
            $sheetRows[] = $row;
        }

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $sheetXml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';

        foreach ($sheetRows as $rowIndex => $row) {
            $excelRow = $rowIndex + 1;
            $sheetXml .= '<row r="' . $excelRow . '">';
            foreach (array_values($row) as $columnIndex => $value) {
                $cell = self::columnName($columnIndex + 1) . $excelRow;
                if ($value === null) {
                    continue;
                }

                if (is_int($value) || is_float($value) || (is_string($value) && is_numeric($value))) {
                    $sheetXml .= '<c r="' . $cell . '"><v>' . htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</v></c>';
                    continue;
                }

                $text = htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
                $sheetXml .= '<c r="' . $cell . '" t="inlineStr"><is><t xml:space="preserve">' . $text . '</t></is></c>';
            }
            $sheetXml .= '</row>';
        }

        $sheetXml .= '</sheetData></worksheet>';
        $workbookXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="' . htmlspecialchars($sheetName, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '" sheetId="1" r:id="rId1"/></sheets></workbook>';

        $files = [
            '[Content_Types].xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
                . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
                . '<Default Extension="xml" ContentType="application/xml"/>'
                . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
                . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
                . '</Types>',
            '_rels/.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
                . '</Relationships>',
            'xl/workbook.xml' => $workbookXml,
            'xl/_rels/workbook.xml.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
                . '</Relationships>',
            'xl/worksheets/sheet1.xml' => $sheetXml,
        ];

        return self::zip($files);
    }

    private static function zip(array $files): string
    {
        $body = '';
        $directory = '';

        foreach ($files as $name => $content) {
            $nameLength = strlen($name);
            $compressed = gzdeflate($content);
            if ($compressed === false) {
                throw new RuntimeException('Unable to compress workbook content.');
            }

            $crc = crc32($content);
            $compressedSize = strlen($compressed);
            $contentSize = strlen($content);
            $offset = strlen($body);

            $body .= pack('VvvvvvVVVvv', 0x04034b50, 20, 0, 8, 0, 0, $crc, $compressedSize, $contentSize, $nameLength, 0)
                . $name . $compressed;

            $directory .= pack('VvvvvvvVVVvvvvvVV', 0x02014b50, 20, 20, 0, 8, 0, 0, $crc, $compressedSize, $contentSize, $nameLength, 0, 0, 0, 0, 0, $offset)
                . $name;
        }

        $directoryOffset = strlen($body);
        $directorySize = strlen($directory);
        $fileCount = count($files);

        return $body . $directory . pack('VvvvvVVv', 0x06054b50, 0, 0, $fileCount, $fileCount, $directorySize, $directoryOffset, 0);
    }

    private static function columnName(int $number): string
    {
        $name = '';
        while ($number > 0) {
            $number--;
            $name = chr(65 + ($number % 26)) . $name;
            $number = intdiv($number, 26);
        }

        return $name;
    }
}
