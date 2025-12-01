<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;

class GradeReportController extends Controller
{
    /**
     * Show grade reports interface
     */
    public function index()
    {
        $academicYears = AcademicYear::orderByDesc('year')->get();
        return view('admin.reports.grade-reports', compact('academicYears'));
    }

    /**
     * Generate GPA report
     */
    public function gpaReport(Request $request)
    {
        $query = Student::whereNotNull('gpa')->with('program');

        if ($request->filled('academic_standing')) {
            $query->where('academic_standing', $request->input('academic_standing'));
        }

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->input('program_id'));
        }

        if ($request->filled('year_level')) {
            $query->where('year_level', $request->input('year_level'));
        }

        $students = $query->orderByDesc('gpa')->paginate(50);

        return view('admin.reports.gpa-report', compact('students'));
    }

    /**
     * Generate irregular students report
     */
    public function irregularStudentsReport()
    {
        $students = Student::where('academic_standing', 'Irregular')
            ->with('program')
            ->orderBy('last_name')
            ->paginate(50);

        $stats = [
            'total' => Student::where('academic_standing', 'Irregular')->count(),
            'by_year' => Student::where('academic_standing', 'Irregular')
                ->selectRaw('year_level, COUNT(*) as count')
                ->groupBy('year_level')
                ->get(),
        ];

        return view('admin.reports.irregular-students', compact('students', 'stats'));
    }

    /**
     * Generate Dean's list report
     */
    public function deansListReport()
    {
        $students = Student::where('academic_standing', 'Dean\'s Lister')
            ->with('program')
            ->orderByDesc('gpa')
            ->paginate(50);

        $stats = [
            'total' => Student::where('academic_standing', 'Dean\'s Lister')->count(),
            'average_gpa' => Student::where('academic_standing', 'Dean\'s Lister')
                ->avg('gpa'),
        ];

        return view('admin.reports.deans-list', compact('students', 'stats'));
    }

    /**
     * Generate grade distribution report
     */
    public function gradeDistributionReport(Request $request)
    {
        $academicYear = $request->filled('academic_year_id')
            ? AcademicYear::find($request->input('academic_year_id'))
            : AcademicYear::where('is_current', true)->first();

        if (!$academicYear) {
            return back()->with('error', 'No academic year selected.');
        }

        $enrollments = Enrollment::where('academic_year_id', $academicYear->id)
            ->whereNotNull('grade')
            ->get();

        $distribution = [
            'A' => $enrollments->where('grade', '>=', 90)->count(),
            'B' => $enrollments->whereBetween('grade', [80, 89])->count(),
            'C' => $enrollments->whereBetween('grade', [70, 79])->count(),
            'D' => $enrollments->whereBetween('grade', [60, 69])->count(),
            'F' => $enrollments->where('grade', '<', 60)->count(),
        ];

        $stats = [
            'total' => $enrollments->count(),
            'average_grade' => round($enrollments->avg('grade'), 2),
            'highest_grade' => $enrollments->max('grade'),
            'lowest_grade' => $enrollments->min('grade'),
        ];

        return view('admin.reports.grade-distribution', compact('academicYear', 'distribution', 'stats'));
    }
}
