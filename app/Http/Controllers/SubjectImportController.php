<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Program;
use App\Models\Department;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectImportController extends Controller
{
    /**
     * Show the import form
     */
    public function create()
    {
        return view('subjects.import');
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subject_import_template.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['Code', 'Name', 'Description', 'Units', 'Program Code', 'Year Level', 'Semester', 'Prerequisites']);
            
            // Example rows
            fputcsv($file, ['MATH101', 'Calculus 1', 'Differential calculus and basic integration', '3', 'BSIT', '1st Year', '1st Semester', '']);
            fputcsv($file, ['CS101', 'Introduction to Programming', 'Fundamentals of programming using Python', '3', 'BSIT', '1st Year', '1st Semester', '']);
            fputcsv($file, ['CS102', 'Data Structures and Algorithms', 'Arrays linked lists trees graphs', '3', 'BSIT', '1st Year', '2nd Semester', 'CS101']);
            fputcsv($file, ['CS201', 'Object-Oriented Programming', 'OOP principles using Java', '3', 'BSIT', '2nd Year', '1st Semester', 'CS101,CS102']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Process the CSV/Excel import
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        
        // Determine file type and parse accordingly
        if ($file->getClientOriginalExtension() === 'xlsx') {
            $data = $this->parseExcel($path);
        } else {
            $data = $this->parseCsv($path);
        }

        if (empty($data)) {
            return back()->withErrors(['file' => 'The file is empty or invalid.']);
        }

        $results = $this->processImport($data);

        // Log import activity
        Activity::create([
            'user_id' => Auth::id(),
            'subject_type' => 'App\\Models\\Subject',
            'subject_id' => null,
            'action' => 'bulk_import',
            'description' => "Imported {$results['success']} subject(s), {$results['failed']} failed",
            'properties' => [
                'success_count' => $results['success'],
                'failed_count' => $results['failed'],
                'errors' => $results['errors'],
            ],
        ]);

        if ($results['failed'] > 0) {
            return redirect()->route('subjects.index')
                ->with('warning', "Imported {$results['success']} subject(s). {$results['failed']} failed. Check activity log for details.")
                ->with('import_errors', $results['errors']);
        }

        return redirect()->route('subjects.index')
            ->with('success', "Successfully imported {$results['success']} subject(s)!");
    }

    /**
     * Parse CSV file
     */
    private function parseCsv($path)
    {
        $data = [];
        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle); // Skip header row
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 8) { // Minimum required columns
                    $data[] = [
                        'code' => $row[0] ?? '',
                        'name' => $row[1] ?? '',
                        'description' => $row[2] ?? '',
                        'units' => $row[3] ?? '',
                        'program_code' => $row[4] ?? '',
                        'year_level' => $row[5] ?? '',
                        'semester' => $row[6] ?? '',
                        'prerequisites' => $row[7] ?? '', // Comma-separated codes
                    ];
                }
            }
            fclose($handle);
        }
        return $data;
    }

    /**
     * Parse Excel file using PhpSpreadsheet
     */
    private function parseExcel($path)
    {
        $data = [];
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            
            // Skip header row
            array_shift($rows);
            
            foreach ($rows as $row) {
                if (count($row) >= 8 && !empty($row[0])) {
                    $data[] = [
                        'code' => $row[0] ?? '',
                        'name' => $row[1] ?? '',
                        'description' => $row[2] ?? '',
                        'units' => $row[3] ?? '',
                        'program_code' => $row[4] ?? '',
                        'year_level' => $row[5] ?? '',
                        'semester' => $row[6] ?? '',
                        'prerequisites' => $row[7] ?? '',
                    ];
                }
            }
        } catch (\Exception $e) {
            return [];
        }
        
        return $data;
    }

    /**
     * Process import data with validation and error tracking
     */
    private function processImport($data)
    {
        $success = 0;
        $failed = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($data as $index => $row) {
                $rowNumber = $index + 2; // +2 because array is 0-indexed and we skipped header
                
                try {
                    // Validate required fields
                    if (empty($row['code']) || empty($row['name']) || empty($row['program_code'])) {
                        throw new \Exception("Missing required fields (code, name, or program_code)");
                    }

                    // Find program by code
                    $program = Program::where('code', $row['program_code'])->first();
                    if (!$program) {
                        throw new \Exception("Program '{$row['program_code']}' not found");
                    }

                    // Validate year level
                    $validYearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
                    if (!in_array($row['year_level'], $validYearLevels)) {
                        throw new \Exception("Invalid year level '{$row['year_level']}'");
                    }

                    // Validate semester
                    $validSemesters = ['1st Semester', '2nd Semester', 'Summer'];
                    if (!in_array($row['semester'], $validSemesters)) {
                        throw new \Exception("Invalid semester '{$row['semester']}'");
                    }

                    // Validate units
                    $units = (int)$row['units'];
                    if ($units < 1 || $units > 10) {
                        throw new \Exception("Units must be between 1 and 10");
                    }

                    // Check for duplicate subject code
                    if (Subject::where('code', $row['code'])->exists()) {
                        throw new \Exception("Subject code '{$row['code']}' already exists");
                    }

                    // Create subject (we'll handle prerequisites in a second pass)
                    $subject = Subject::create([
                        'code' => $row['code'],
                        'name' => $row['name'],
                        'description' => $row['description'] ?: null,
                        'units' => $units,
                        'program_id' => $program->id,
                        'department_id' => $program->department_id,
                        'year_level' => $row['year_level'],
                        'semester' => $row['semester'],
                        'is_active' => true,
                        'prerequisite_subject_ids' => [], // Set later
                    ]);

                    $success++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Row {$rowNumber}: {$e->getMessage()}";
                }
            }

            // Second pass: Handle prerequisites
            foreach ($data as $row) {
                if (empty($row['prerequisites'])) {
                    continue;
                }

                $subject = Subject::where('code', $row['code'])->first();
                if (!$subject) {
                    continue;
                }

                // Parse prerequisite codes (comma-separated)
                $prereqCodes = array_map('trim', explode(',', $row['prerequisites']));
                $prereqIds = [];

                foreach ($prereqCodes as $prereqCode) {
                    if (empty($prereqCode)) {
                        continue;
                    }

                    $prereqSubject = Subject::where('code', $prereqCode)->first();
                    if ($prereqSubject) {
                        $prereqIds[] = $prereqSubject->id;
                    }
                }

                if (!empty($prereqIds)) {
                    $subject->update(['prerequisite_subject_ids' => $prereqIds]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'success' => $success,
            'failed' => $failed,
            'errors' => $errors,
        ];
    }
}
