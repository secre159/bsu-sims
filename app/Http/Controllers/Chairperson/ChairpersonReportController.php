<?php

namespace App\Http\Controllers\Chairperson;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ChairpersonReportController extends Controller
{
    /**
     * Display reports landing page
     */
    public function index()
    {
        $chairperson = auth()->user();
        
        // Get basic stats for the landing page
        $departmentSubjects = Subject::where('department_id', $chairperson->department_id)->pluck('id');
        
        $totalStudents = Student::whereHas('enrollments', function ($query) use ($departmentSubjects) {
            $query->whereIn('subject_id', $departmentSubjects);
        })->distinct()->count();
        
        $totalEnrollments = Enrollment::whereIn('subject_id', $departmentSubjects)->count();
        $gradedEnrollments = Enrollment::whereIn('subject_id', $departmentSubjects)
            ->whereNotNull('grade')->count();
        
        $irregularStudents = Student::whereHas('enrollments', function ($query) use ($departmentSubjects) {
            $query->whereIn('subject_id', $departmentSubjects)
                ->where(function($q) {
                    $q->where('status', 'Failed')
                      ->orWhere('grade', 'INC')
                      ->orWhere('grade', 'IP');
                });
        })->distinct()->count();
        
        return view('chairperson.reports.index', compact(
            'totalStudents', 
            'totalEnrollments', 
            'gradedEnrollments', 
            'irregularStudents'
        ));
    }
    
    /**
     * Display students list report
     */
    public function studentsList(Request $request)
    {
        $chairperson = auth()->user();
        $departmentSubjects = Subject::where('department_id', $chairperson->department_id)->pluck('id');
        
        // Get unique students with enrollments in department's subjects
        $query = Student::whereHas('enrollments', function ($q) use ($departmentSubjects) {
            $q->whereIn('subject_id', $departmentSubjects);
        })->with(['program']);
        
        // Apply filters
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }
        
        if ($request->filled('year_level')) {
            $query->where('year_level', $request->year_level);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('academic_year_id')) {
            $query->whereHas('enrollments', function ($q) use ($request, $departmentSubjects) {
                $q->whereIn('subject_id', $departmentSubjects)
                  ->where('academic_year_id', $request->academic_year_id);
            });
        }
        
        $students = $query->orderBy('last_name')->orderBy('first_name')->get();
        
        // Get filter options
        $programs = Program::where('department_id', $chairperson->department_id)->get();
        $academicYears = AcademicYear::orderBy('year_code', 'desc')->orderBy('semester')->get();
        
        return view('chairperson.reports.students-list', compact('students', 'programs', 'academicYears'));
    }
    
    /**
     * Display grades summary report
     */
    public function gradesSummary(Request $request)
    {
        $chairperson = auth()->user();
        
        $query = Subject::where('department_id', $chairperson->department_id)
            ->withCount(['enrollments as total_enrollments'])
            ->withCount(['enrollments as graded_count' => function ($q) {
                $q->whereNotNull('grade');
            }])
            ->withCount(['enrollments as pending_count' => function ($q) {
                $q->whereNull('grade');
            }])
            ->withCount(['enrollments as passed_count' => function ($q) {
                $q->whereNotNull('grade')
                  ->where('grade', '!=', '5.00')
                  ->where('grade', '!=', 'INC')
                  ->where('grade', '!=', 'IP')
                  ->whereRaw('CAST(grade AS DECIMAL(5,2)) < 4.0');
            }])
            ->withCount(['enrollments as failed_count' => function ($q) {
                $q->where('grade', '5.00');
            }]);
        
        // Filter by academic year if specified
        if ($request->filled('academic_year_id')) {
            $query->whereHas('enrollments', function ($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }
        
        // Filter by subject if specified
        if ($request->filled('subject_id')) {
            $query->where('id', $request->subject_id);
        }
        
        $subjects = $query->orderBy('code')->get();
        
        // Calculate overall stats
        $totalEnrollments = $subjects->sum('total_enrollments');
        $totalGraded = $subjects->sum('graded_count');
        $totalPending = $subjects->sum('pending_count');
        $totalPassed = $subjects->sum('passed_count');
        $totalFailed = $subjects->sum('failed_count');
        $completionRate = $totalEnrollments > 0 ? round(($totalGraded / $totalEnrollments) * 100, 1) : 0;
        
        // Get filter options
        $allSubjects = Subject::where('department_id', $chairperson->department_id)
            ->orderBy('code')->get();
        $academicYears = AcademicYear::orderBy('year_code', 'desc')->orderBy('semester')->get();
        
        return view('chairperson.reports.grades-summary', compact(
            'subjects', 
            'allSubjects', 
            'academicYears',
            'totalEnrollments',
            'totalGraded',
            'totalPending',
            'totalPassed',
            'totalFailed',
            'completionRate'
        ));
    }
    
    /**
     * Display irregular students report
     */
    public function irregularStudents(Request $request)
    {
        $chairperson = auth()->user();
        $departmentSubjects = Subject::where('department_id', $chairperson->department_id)->pluck('id');
        
        // Get students with failed/INC/IP grades in department subjects
        $students = Student::whereHas('enrollments', function ($query) use ($departmentSubjects) {
            $query->whereIn('subject_id', $departmentSubjects)
                ->where(function($q) {
                    $q->where('status', 'Failed')
                      ->orWhere('grade', 'INC')
                      ->orWhere('grade', 'IP')
                      ->orWhere('grade', '5.00');
                });
        })
        ->with(['program', 'enrollments' => function($query) use ($departmentSubjects) {
            $query->whereIn('subject_id', $departmentSubjects)
                ->where(function($q) {
                    $q->where('status', 'Failed')
                      ->orWhere('grade', 'INC')
                      ->orWhere('grade', 'IP')
                      ->orWhere('grade', '5.00');
                })
                ->with(['subject', 'academicYear'])
                ->orderBy('academic_year_id', 'desc');
        }])
        ->orderBy('last_name')
        ->orderBy('first_name')
        ->get();
        
        return view('chairperson.reports.irregular-students', compact('students'));
    }
    
    /**
     * Export students list to CSV
     */
    public function exportStudents(Request $request)
    {
        $chairperson = auth()->user();
        $departmentSubjects = Subject::where('department_id', $chairperson->department_id)->pluck('id');
        
        $students = Student::whereHas('enrollments', function ($q) use ($departmentSubjects) {
            $q->whereIn('subject_id', $departmentSubjects);
        })->with('program')->distinct()->orderBy('last_name')->orderBy('first_name')->get();
        
        $filename = 'students_' . $chairperson->department->code . '_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Student ID',
                'Last Name',
                'First Name',
                'Middle Name',
                'Program',
                'Year Level',
                'Status',
                'Contact Number',
                'Email'
            ]);
            
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->student_id,
                    $student->last_name,
                    $student->first_name,
                    $student->middle_name,
                    $student->program->code ?? 'N/A',
                    $student->year_level,
                    $student->status,
                    $student->contact_number,
                    $student->email_address
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export grades summary to CSV
     */
    public function exportGradesSummary(Request $request)
    {
        $chairperson = auth()->user();
        
        $subjects = Subject::where('department_id', $chairperson->department_id)
            ->withCount(['enrollments as total_enrollments'])
            ->withCount(['enrollments as graded_count' => function ($q) {
                $q->whereNotNull('grade');
            }])
            ->withCount(['enrollments as pending_count' => function ($q) {
                $q->whereNull('grade');
            }])
            ->withCount(['enrollments as passed_count' => function ($q) {
                $q->whereNotNull('grade')
                  ->where('grade', '!=', '5.00')
                  ->where('grade', '!=', 'INC')
                  ->where('grade', '!=', 'IP')
                  ->whereRaw('CAST(grade AS DECIMAL(5,2)) < 4.0');
            }])
            ->withCount(['enrollments as failed_count' => function ($q) {
                $q->where('grade', '5.00');
            }])
            ->orderBy('code')
            ->get();
        
        $filename = 'grades_summary_' . $chairperson->department->code . '_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($subjects) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Subject Code',
                'Subject Name',
                'Total Enrollments',
                'Graded',
                'Pending',
                'Passed',
                'Failed',
                'Completion Rate %'
            ]);
            
            foreach ($subjects as $subject) {
                $completionRate = $subject->total_enrollments > 0 
                    ? round(($subject->graded_count / $subject->total_enrollments) * 100, 1) 
                    : 0;
                
                fputcsv($file, [
                    $subject->code,
                    $subject->name,
                    $subject->total_enrollments,
                    $subject->graded_count,
                    $subject->pending_count,
                    $subject->passed_count,
                    $subject->failed_count,
                    $completionRate . '%'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
