<?php

namespace App\Http\Controllers\Chairperson;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\GradeImportBatch;
use App\Models\GradeImportRecord;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Services\GradeImportService;
use App\Services\ColumnMapper;
use App\Services\GradeNormalizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GradeImportController extends Controller
{
    private GradeImportService $importService;

    public function __construct(GradeImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Show form for uploading Excel file
     */
    public function create()
    {
        $chairperson = auth()->user();
        
        // Check pending batches limit (max 3)
        $pendingBatchesCount = GradeImportBatch::where('chairperson_id', $chairperson->id)
            ->whereIn('status', ['pending', 'ready'])
            ->count();
        
        if ($pendingBatchesCount >= 3) {
            return redirect()->route('chairperson.grade-batches.index')
                ->with('error', 'You have reached the maximum limit of 3 pending batches. Please submit or delete existing batches before creating a new import.');
        }
        
        $subjects = Subject::where('department_id', $chairperson->department_id)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();
        
        return view('chairperson.grades.import.create', compact('subjects', 'pendingBatchesCount'));
    }
    
    /**
     * Download pre-populated CSV template for a subject
     */
    public function downloadTemplate(Request $request, Subject $subject)
    {
        $chairperson = auth()->user();
        
        // Verify subject belongs to chairperson's department
        if ($subject->department_id !== $chairperson->department_id) {
            abort(403, 'You cannot access this subject.');
        }
        
        // Get all enrollments for this subject
        $enrollments = Enrollment::where('subject_id', $subject->id)
            ->with(['student', 'academicYear'])
            ->orderBy('student_id')
            ->get();
        
        if ($enrollments->isEmpty()) {
            return back()->with('error', 'No students are enrolled in this subject yet.');
        }
        
        $filename = 'grade_template_' . $subject->code . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($enrollments, $subject) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'student_id',
                'student_name',
                'subject_code',
                'grade',
                'remarks'
            ]);
            
            // Data rows - pre-filled with student info, empty grade column
            foreach ($enrollments as $enrollment) {
                fputcsv($file, [
                    $enrollment->student->student_id,
                    $enrollment->student->last_name . ', ' . $enrollment->student->first_name,
                    $subject->code,
                    '', // Empty grade column for chairperson to fill
                    '' // Empty remarks column
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Upload file and go to column mapping step
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $chairperson = auth()->user();
        $file = $validated['file'];
        $fileName = $file->getClientOriginalName();
        $filePath = $file->getPathname();

        try {
            // Store file in storage for later use
            $storagePath = Storage::disk('local')->putFile('grade-imports', $file);
            $fullStoragePath = Storage::disk('local')->path($storagePath);

            // Load file and auto-detect columns
            $spreadsheet = IOFactory::load($fullStoragePath);
            $worksheet = $spreadsheet->getActiveSheet();

            $columnMapper = app(ColumnMapper::class);
            $headers = $columnMapper->getHeaders($worksheet);
            $autoDetectedMapping = $columnMapper->autoDetect($worksheet);

            // Store file path in session
            session([
                'import_storage_path' => $storagePath,
                'import_file_name' => $fileName,
                'import_headers' => $headers,
            ]);

            // Show column mapping form
            return view('chairperson.grades.import.map-columns', [
                'headers' => $headers,
                'autoDetectedMapping' => $autoDetectedMapping,
                'fileName' => $fileName,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error reading file: ' . $e->getMessage());
        }
    }

    /**
     * Map columns and show preview
     */
    public function preview(Request $request)
    {
        $validated = $request->validate([
            'student_id_column' => 'required|integer|min:0',
            'subject_code_column' => 'required|integer|min:0',
            'grade_column' => 'required|integer|min:0',
        ]);

        $storagePath = session('import_storage_path');
        $fileName = session('import_file_name');
        $headers = session('import_headers');

        if (!$storagePath) {
            return redirect()->route('chairperson.grade-import.create')
                ->with('error', 'File session expired. Please upload again.');
        }

        $filePath = Storage::disk('local')->path($storagePath);
        if (!file_exists($filePath)) {
            return redirect()->route('chairperson.grade-import.create')
                ->with('error', 'File session expired. Please upload again.');
        }

        $columnMapping = [
            'student_id' => $validated['student_id_column'],
            'subject_code' => $validated['subject_code_column'],
            'grade' => $validated['grade_column'],
        ];

        // Store mapping in session
        session(['import_column_mapping' => $columnMapping]);

        try {
            // Load file and get preview data
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();

            $columnMapper = app(ColumnMapper::class);
            $gradeNormalizer = app(GradeNormalizer::class);

            $previewData = [];
            $rowCount = 0;

            // Get first 10 rows for preview
            foreach ($worksheet->getRowIterator(2) as $row) {
                if ($rowCount >= 10) break;

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                if (empty(array_filter($rowData))) continue;

                $extracted = $columnMapper->extractRowData($rowData, $columnMapping);
                $normalized = $gradeNormalizer->normalize($extracted['grade']);

                $previewData[] = [
                    'student_id' => $extracted['student_id'],
                    'subject_code' => $extracted['subject_code'],
                    'grade_raw' => $extracted['grade'],
                    'grade_normalized' => $normalized['valid'] ? $normalized['value'] : null,
                    'grade_error' => !$normalized['valid'] ? $normalized['error'] : null,
                ];

                $rowCount++;
            }

            return view('chairperson.grades.import.preview', [
                'previewData' => $previewData,
                'columnMapping' => $columnMapping,
                'headers' => $headers,
                'fileName' => $fileName,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error previewing file: ' . $e->getMessage());
        }
    }

    /**
     * Go back to column mapping from preview
     */
    public function backToMapping(Request $request)
    {
        $headers = session('import_headers');
        $storagePath = session('import_storage_path');
        $fileName = session('import_file_name');

        if (!$headers || !$storagePath) {
            return redirect()->route('chairperson.grade-import.create')
                ->with('error', 'Session expired. Please upload again.');
        }

        // Get auto-detected mapping from the stored file
        try {
            $filePath = Storage::disk('local')->path($storagePath);
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $columnMapper = app(ColumnMapper::class);
            $autoDetectedMapping = $columnMapper->autoDetect($worksheet);

            return view('chairperson.grades.import.map-columns', [
                'headers' => $headers,
                'autoDetectedMapping' => $autoDetectedMapping,
                'fileName' => $fileName,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading file: ' . $e->getMessage());
        }
    }

    /**
     * Process and import the batch
     */
    public function processImport(Request $request)
    {
        $storagePath = session('import_storage_path');
        $columnMapping = session('import_column_mapping');
        $fileName = session('import_file_name');

        if (!$storagePath || !$columnMapping) {
            return redirect()->route('chairperson.grade-import.create')
                ->with('error', 'Session expired. Please start over.');
        }

        $filePath = Storage::disk('local')->path($storagePath);

        $chairperson = auth()->user();

        // Create import batch record
        $batch = GradeImportBatch::create([
            'chairperson_id' => $chairperson->id,
            'file_name' => $fileName,
            'total_records' => 0,
            'success_count' => 0,
            'error_count' => 0,
            'status' => 'processing',
        ]);

        try {
            // Process file with column mapping
            $this->importService->processFile($filePath, $batch, $chairperson, $columnMapping);

            // Clean up temporary file
            Storage::disk('local')->delete($storagePath);

            // Clear session
            session()->forget(['import_storage_path', 'import_file_name', 'import_headers', 'import_column_mapping']);

            return redirect()->route('chairperson.grade-batches.show', $batch)
                ->with('success', 'File processed successfully. Review the results below.');
        } catch (\Exception $e) {
            $batch->delete();
            return back()->with('error', 'Error processing file: ' . $e->getMessage());
        }
    }


    /**
     * Submit batch for approval (sends to admin approval queue)
     */
    public function submit(GradeImportBatch $batch)
    {
        $chairperson = auth()->user();
        
        if ($batch->chairperson_id !== $chairperson->id) {
            abort(403, 'You cannot submit this batch.');
        }

        // Check if there are any error records
        if ($batch->error_count > 0) {
            return back()->with('error', 'Cannot submit batch with errors. Please resolve all errors first.');
        }

        if ($batch->status !== 'pending' && $batch->status !== 'ready') {
            return back()->with('error', 'Can only submit pending or ready batches.');
        }

        try {
            // Submit for approval (don't apply grades yet)
            $batch->update([
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            // Log batch submission activity
            Activity::create([
                'user_id' => $chairperson->id,
                'subject_type' => GradeImportBatch::class,
                'subject_id' => $batch->id,
                'action' => 'grade_batch_submitted',
                'description' => "Chairperson {$chairperson->name} submitted grade import batch '{$batch->file_name}' for admin approval",
                'properties' => [
                    'batch_id' => $batch->id,
                    'file_name' => $batch->file_name,
                    'total_records' => $batch->total_records,
                    'success_count' => $batch->success_count,
                    'department_id' => $chairperson->department_id,
                ],
            ]);

            return redirect()->route('chairperson.grade-batches.show', $batch)
                ->with('success', 'Batch submitted successfully for admin approval.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error submitting batch: ' . $e->getMessage());
        }
    }
}
