<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ColumnMapper
{
    /**
     * Patterns to match column headers
     */
    private array $patterns = [
        'student_id' => ['student.*id', 'student', 'stud.*id', 'id.*number', 'student.*number'],
        'subject_code' => ['subject.*code', 'subject', 'course.*code', 'code', 'subject.*no', 'course.*no'],
        'grade' => ['grade', 'score', 'mark', 'points', 'rating', 'assessment'],
    ];

    /**
     * Auto-detect column indices from worksheet headers
     * Returns array: ['student_id' => 0, 'subject_code' => 1, 'grade' => 2]
     */
    public function autoDetect(Worksheet $worksheet): array
    {
        $headers = $this->getHeaders($worksheet);
        $mapping = [];

        foreach ($this->patterns as $field => $patterns) {
            $detectedIndex = $this->findColumnIndex($headers, $patterns);
            if ($detectedIndex !== null) {
                $mapping[$field] = $detectedIndex;
            }
        }

        return $mapping;
    }

    /**
     * Validate that all required columns are mapped
     */
    public function validateMapping(array $mapping): array
    {
        $required = ['student_id', 'subject_code', 'grade'];
        $missing = [];

        foreach ($required as $field) {
            if (!isset($mapping[$field]) || $mapping[$field] === null) {
                $missing[] = $field;
            }
        }

        return $missing;
    }

    /**
     * Get all headers from worksheet
     */
    public function getHeaders(Worksheet $worksheet): array
    {
        $headers = [];
        $firstRow = $worksheet->getRowIterator(1, 1);

        foreach ($firstRow as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $cell) {
                $value = $cell->getValue();
                if ($value !== null && trim($value) !== '') {
                    $headers[] = (string)$value;
                }
            }
        }

        return $headers;
    }

    /**
     * Find column index using pattern matching (case-insensitive)
     */
    private function findColumnIndex(array $headers, array $patterns): ?int
    {
        foreach ($headers as $index => $header) {
            $headerLower = strtolower(trim($header));

            foreach ($patterns as $pattern) {
                // Simple pattern matching: convert pattern to regex
                $regex = '/^' . str_replace('.*', '.*', $pattern) . '$/i';
                if (preg_match($regex, $headerLower)) {
                    return $index;
                }
            }
        }

        return null;
    }

    /**
     * Extract data from row using column mapping
     */
    public function extractRowData(array $rowData, array $mapping): array
    {
        return [
            'student_id' => (string)($rowData[$mapping['student_id']] ?? ''),
            'subject_code' => (string)($rowData[$mapping['subject_code']] ?? ''),
            'grade' => $rowData[$mapping['grade']] ?? '',
        ];
    }
}
