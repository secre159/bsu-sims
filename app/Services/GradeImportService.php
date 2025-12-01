<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\GradeImportBatch;
use App\Models\GradeImportRecord;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Storage;

class GradeImportService
{
    private ColumnMapper $columnMapper;
    private GradeNormalizer $gradeNormalizer;
    private array $errors = [];

    public function __construct(ColumnMapper $columnMapper, GradeNormalizer $gradeNormalizer)
    {
        $this->columnMapper = $columnMapper;
        $this->gradeNormalizer = $gradeNormalizer;
    }

    /**
     * Parse and validate Excel file with flexible column mapping
     */
    public function processFile(string $filePath, GradeImportBatch $batch, User $chairperson, ?array $columnMapping = null): void
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();

            // Auto-detect or use provided column mapping
            if ($columnMapping === null) {
                $columnMapping = $this->columnMapper->autoDetect($worksheet);
            }

            // Validate that all required columns are mapped
            $missing = $this->columnMapper->validateMapping($columnMapping);
            if (!empty($missing)) {
                throw new \Exception('Missing required columns: ' . implode(', ', $missing));
            }

            // Store mapping in batch for later reference
            $batch->update(['column_mapping' => $columnMapping]);

            $headerIndices = $columnMapping;

            $records = [];
            $totalRecords = 0;
            $successCount = 0;
            $errorCount = 0;

            // Process data rows (starting from row 2, after headers)
            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                // Skip empty rows
                if (empty(array_filter($rowData))) {
                    continue;
                }

                $totalRecords++;

                // Extract values using column mapping
                $extractedData = $this->columnMapper->extractRowData($rowData, $headerIndices);
                $studentId = $extractedData['student_id'];
                $subjectCode = $extractedData['subject_code'];
                $grade = $extractedData['grade'];

                // Validate record
                $validation = $this->validateRecord($studentId, $subjectCode, $grade, $chairperson);

                if ($validation['valid']) {
                    $successCount++;
                    $record = [
                        'status' => 'matched',
                        'enrollment_id' => $validation['enrollment_id'],
                        'student_id_number' => $studentId,
                        'subject_code' => $subjectCode,
                        'grade' => $validation['grade'],
                        'error_message' => null,
                    ];
                } else {
                    $errorCount++;
                    $record = [
                        'status' => 'error',
                        'enrollment_id' => null,
                        'student_id_number' => $studentId,
                        'subject_code' => $subjectCode,
                        'grade' => $validation['grade'],
                        'error_message' => $validation['error'],
                    ];
                }

                $records[] = $record;
            }

            // Create import records in database
            foreach ($records as $record) {
                GradeImportRecord::create([
                    'grade_import_batch_id' => $batch->id,
                    ...$record,
                ]);
            }

            // Update batch totals
            $batch->update([
                'total_records' => $totalRecords,
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'status' => $errorCount > 0 ? 'pending' : 'ready',
            ]);

        } catch (\Exception $e) {
            $batch->update([
                'status' => 'failed',
            ]);
            throw $e;
        }
    }


    /**
     * Validate individual record
     */
    private function validateRecord(string $studentId, string $subjectCode, mixed $grade, User $chairperson): array
    {
        // Validate student ID format
        if (empty($studentId)) {
            return [
                'valid' => false,
                'error' => 'Student ID is required',
                'grade' => null,
            ];
        }

        // Validate subject code format
        if (empty($subjectCode)) {
            return [
                'valid' => false,
                'error' => 'Subject Code is required',
                'grade' => null,
            ];
        }

        // Normalize and validate grade
        $normalizedGrade = $this->gradeNormalizer->normalize($grade);
        if (!$normalizedGrade['valid']) {
            return [
                'valid' => false,
                'error' => $normalizedGrade['error'],
                'grade' => null,
            ];
        }

        $normalizedValue = $normalizedGrade['value'];

        // Store normalized grade in context for later use
        $gradeToStore = $normalizedValue;

        // Find student by ID
        $student = Student::where('student_id', $studentId)->first();
        if (!$student) {
            return [
                'valid' => false,
                'error' => "Student {$studentId} not found",
                'grade' => null,
            ];
        }

        // Find subject by code in chairperson's department
        $subject = Subject::where('code', $subjectCode)
            ->where('department_id', $chairperson->department_id)
            ->first();

        if (!$subject) {
            return [
                'valid' => false,
                'error' => "Subject {$subjectCode} not found in your department",
                'grade' => null,
            ];
        }

        // Find enrollment
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('subject_id', $subject->id)
            ->first();

        if (!$enrollment) {
            return [
                'valid' => false,
                'error' => "Student is not enrolled in {$subjectCode}",
                'grade' => null,
            ];
        }

        return [
            'valid' => true,
            'enrollment_id' => $enrollment->id,
            'grade' => $gradeToStore,
        ];
    }

}
