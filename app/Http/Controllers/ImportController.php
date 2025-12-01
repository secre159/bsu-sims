<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{
    public function showImportForm()
    {
        $programs = Program::all();
        return view('students.import', compact('programs'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $fileContents = file($file->getPathname());

        $imported = 0;
        $errors = [];
        $row = 0;

        DB::beginTransaction();

        try {
            foreach ($fileContents as $line) {
                $row++;
                
                // Skip header row
                if ($row === 1) {
                    continue;
                }

                $data = str_getcsv($line);
                
                // Skip empty rows
                if (empty(array_filter($data))) {
                    continue;
                }

                // Map CSV columns: student_id, last_name, first_name, middle_name, email, contact_number, program_code, year_level, status
                $validator = Validator::make([
                    'student_id' => $data[0] ?? null,
                    'last_name' => $data[1] ?? null,
                    'first_name' => $data[2] ?? null,
                    'middle_name' => $data[3] ?? null,
                    'email' => $data[4] ?? null,
                    'contact_number' => $data[5] ?? null,
                    'program_code' => $data[6] ?? null,
                    'year_level' => $data[7] ?? null,
                    'status' => $data[8] ?? 'Active',
                ], [
                    'student_id' => 'required|unique:students,student_id',
                    'last_name' => 'required',
                    'first_name' => 'required',
                    'email' => 'required|email|unique:students,email',
                    'program_code' => 'required|exists:programs,code',
                    'year_level' => 'required',
                ]);

                if ($validator->fails()) {
                    $errors[] = "Row {$row}: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                $program = Program::where('code', $data[6])->first();

                Student::create([
                    'student_id' => $data[0],
                    'last_name' => $data[1],
                    'first_name' => $data[2],
                    'middle_name' => $data[3] ?? null,
                    'email' => $data[4],
                    'contact_number' => $data[5] ?? null,
                    'program_id' => $program->id,
                    'year_level' => $data[7],
                    'status' => $data[8] ?? 'Active',
                    'enrollment_date' => now(),
                ]);

                $imported++;
            }

            DB::commit();

            $message = "Successfully imported {$imported} students.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " rows had errors.";
            }

            return redirect()->route('students.index')
                ->with('success', $message)
                ->with('import_errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="student_import_template.csv"',
        ];

        $columns = [
            'student_id',
            'last_name',
            'first_name',
            'middle_name',
            'email',
            'contact_number',
            'program_code',
            'year_level',
            'status'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Add sample row
            fputcsv($file, [
                '2024-0001',
                'Dela Cruz',
                'Juan',
                'Santos',
                'juan.delacruz@student.bsu.edu.ph',
                '09123456789',
                'BSIT',
                '1st Year',
                'Active'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
