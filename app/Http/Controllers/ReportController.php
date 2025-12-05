<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Program;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function studentsList(Request $request)
    {
        $query = Student::with('program');

        // Apply filters if provided
        if ($request->filled('program')) {
            $query->where('program_id', $request->program);
        }

        if ($request->filled('year_level')) {
            $query->where('year_level', $request->year_level);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('last_name')->get();
        $programs = Program::where('is_active', true)->get();

        return view('reports.students', compact('students', 'programs'));
    }

    public function programsList()
    {
        $programs = Program::withCount('students')
            ->with(['students' => function($query) {
                $query->orderBy('year_level')->orderBy('last_name');
            }])
            ->get();

        return view('reports.programs', compact('programs'));
    }

    public function yearLevelsList()
    {
        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'];
        $studentsByYear = [];

        foreach ($yearLevels as $year) {
            $studentsByYear[$year] = Student::with('program')
                ->where('year_level', $year)
                ->orderBy('last_name')
                ->get();
        }

        return view('reports.year-levels', compact('studentsByYear'));
    }

    public function exportStudents(Request $request)
    {
        $students = Student::with('program')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $filename = 'students_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Student ID',
                'Last Name',
                'First Name',
                'Middle Name',
                'Gender',
                'Birthdate',
                'Contact Number',
                'Email',
                'Address',
                'Program',
                'Year Level',
                'Status',
                'Enrollment Date'
            ]);

            // Add data rows
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->student_id,
                    $student->last_name,
                    $student->first_name,
                    $student->middle_name,
                    $student->gender,
                    $student->birthdate ? $student->birthdate->format('Y-m-d') : '',
                    $student->contact_number,
                    $student->email,
                    $student->address,
                    $student->program->code ?? 'N/A',
                    $student->year_level,
                    $student->status,
                    $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function studentsListPdf(Request $request)
    {
        $query = Student::with('program');

        // Apply filters if provided
        if ($request->filled('program')) {
            $query->where('program_id', $request->program);
        }

        if ($request->filled('year_level')) {
            $query->where('year_level', $request->year_level);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('last_name')->get();
        $filterDesc = $this->getFilterDescription($request);

        $pdf = Pdf::loadView('reports.pdf.students', compact('students', 'filterDesc'))
            ->setPaper('a4', 'portrait');
        
        return $pdf->download('students-report-' . date('Y-m-d') . '.pdf');
    }

    public function programsListPdf()
    {
        $programs = Program::withCount('students')
            ->with(['students' => function($query) {
                $query->orderBy('year_level')->orderBy('last_name');
            }])
            ->get();

        $pdf = Pdf::loadView('reports.pdf.programs', compact('programs'))
            ->setPaper('a4', 'portrait');
        
        return $pdf->download('programs-report-' . date('Y-m-d') . '.pdf');
    }

    public function yearLevelsListPdf()
    {
        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'];
        $studentsByYear = [];

        foreach ($yearLevels as $year) {
            $studentsByYear[$year] = Student::with('program')
                ->where('year_level', $year)
                ->orderBy('last_name')
                ->get();
        }

        $pdf = Pdf::loadView('reports.pdf.year-levels', compact('studentsByYear'))
            ->setPaper('a4', 'portrait');
        
        return $pdf->download('year-levels-report-' . date('Y-m-d') . '.pdf');
    }

    private function getFilterDescription(Request $request)
    {
        $filters = [];
        
        if ($request->filled('program')) {
            $program = Program::find($request->program);
            $filters[] = 'Program: ' . ($program->code ?? 'Unknown');
        }
        
        if ($request->filled('year_level')) {
            $filters[] = 'Year Level: ' . $request->year_level;
        }
        
        if ($request->filled('status')) {
            $filters[] = 'Status: ' . $request->status;
        }
        
        return empty($filters) ? 'All Students' : implode(' | ', $filters);
    }
}
