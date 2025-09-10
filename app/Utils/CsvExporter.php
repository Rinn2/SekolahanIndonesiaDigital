<?php
// app/Utils/CsvExporter.php

namespace App\Utils;

use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExporter
{
    public function export(array $headers, array $data, string $filename): StreamedResponse
    {
        return new StreamedResponse(function () use ($headers, $data) {
            $handle = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel display
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write headers
            fputcsv($handle, $headers);
            
            // Write data
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }
            
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Transfer-Encoding' => 'binary',
        ]);
    }

    public function exportFromCollection($collection, array $headers, callable $transformer, string $filename): StreamedResponse
    {
        $data = $collection->map($transformer)->toArray();
        return $this->export($headers, $data, $filename);
    }
}