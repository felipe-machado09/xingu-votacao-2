<?php

namespace App\Services;

use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Cell\StringCell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelExport
{
    public static function download(string $filename, array $headers, iterable $rows): BinaryFileResponse
    {
        $path = tempnam(sys_get_temp_dir(), 'xingu-export-');
        if ($path === false) {
            throw new \RuntimeException('Não foi possível criar o arquivo de exportação.');
        }

        $writer = new Writer;
        try {
            $writer->openToFile($path);
            $writer->addRow(static::row($headers));
            foreach ($rows as $row) {
                $writer->addRow(static::row($row));
            }
            $writer->close();
        } catch (\Throwable $exception) {
            unlink($path);
            throw $exception;
        }

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private static function row(array $values): Row
    {
        return new Row(array_map(
            fn ($value) => is_string($value) ? new StringCell($value, null) : Cell::fromValue($value),
            $values
        ));
    }

    public static function metrics(array $data, string $prefix = ''): \Generator
    {
        foreach ($data as $key => $value) {
            $label = $prefix.str_replace('_', ' ', (string) $key);
            if (is_array($value)) {
                yield from static::metrics($value, $label.' / ');
            } else {
                yield [$label, $value];
            }
        }
    }
}
