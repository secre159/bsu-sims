<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $studentUser = Auth::guard('student')->user();
        $student = $studentUser->student;

        // Get current academic year
        $currentYear = \App\Models\AcademicYear::where('is_current', true)->first();

        // Get current enrollments
        $currentEnrollments = $student->enrollments()
            ->with(['subject', 'academicYear'])
            ->whereIn('status', ['Enrolled', 'Completed'])
            ->when($currentYear, function ($query) use ($currentYear) {
                return $query->where('academic_year_id', $currentYear->id);
            })
            ->get();

        // Get all enrollments grouped by academic year
        $allEnrollments = $student->enrollments()
            ->with(['subject', 'academicYear'])
            ->whereIn('status', ['Enrolled', 'Completed', 'Failed'])
            ->get();

        // Sort by academic year code (chronological order) descending
        $allEnrollments = $allEnrollments->sortByDesc(function ($enrollment) {
            return $enrollment->academicYear->year_code ?? '';
        });

        // Group by year level first, then by semester
        $enrollmentsByYearLevel = $allEnrollments->groupBy(function ($enrollment) {
            return $enrollment->subject->year_level ?? 'Unknown';
        });

        // Sort year levels (1st Year, 2nd Year, etc.) in descending order
        $yearLevelOrder = ['5th Year', '4th Year', '3rd Year', '2nd Year', '1st Year'];
        $enrollmentsByYearLevel = $enrollmentsByYearLevel->sortBy(function ($items, $yearLevel) use ($yearLevelOrder) {
            return array_search($yearLevel, $yearLevelOrder);
        });

        // Within each year level, group by semester
        $enrollmentsByYearLevel = $enrollmentsByYearLevel->map(function ($yearLevelEnrollments) {
            return $yearLevelEnrollments->groupBy(function ($enrollment) {
                if ($enrollment->academicYear) {
                    return $enrollment->academicYear->year_code . ' - ' . $enrollment->academicYear->semester;
                }
                return 'No Academic Year';
            });
        });

        // Get all completed enrollments
        $completedCourses = $student->enrollments()
            ->with('subject')
            ->where('status', 'Completed')
            ->get();

        // Use calculated GWA from database (maintained by GwaCalculationService)
        $overallGwa = $student->gwa;

        // Calculate current semester GWA
        $currentSemesterEnrollments = $currentEnrollments->where('status', 'Completed');
        $currentSemesterGwa = $this->calculateGWA($currentSemesterEnrollments);

        // Get quick stats
        $totalUnits = $currentEnrollments->sum(function($enrollment) {
            return $enrollment->subject->units ?? 0;
        });
        $enrollmentCount = $currentEnrollments->count();
        $completedUnits = $completedCourses->sum(function($enrollment) {
            return $enrollment->subject->units ?? 0;
        });
        $enrollmentsWithGrades = $currentEnrollments->whereNotNull('grade')->count();
        $enrollmentsWithoutGrades = $currentEnrollments->whereNull('grade')->count();

        // Grade statistics
        $gradeStats = [
            'excellent' => $completedCourses->filter(fn($e) => $e->grade && $e->grade <= 1.75)->count(),
            'good' => $completedCourses->filter(fn($e) => $e->grade && $e->grade > 1.75 && $e->grade <= 2.5)->count(),
            'fair' => $completedCourses->filter(fn($e) => $e->grade && $e->grade > 2.5 && $e->grade < 4.0)->count(),
            'failed' => $completedCourses->filter(fn($e) => $e->grade && $e->grade >= 4.0)->count(),
        ];

        return view('student.dashboard', compact(
            'student',
            'currentEnrollments',
            'completedCourses',
            'enrollmentsByYearLevel',
            'overallGwa',
            'currentSemesterGwa',
            'totalUnits',
            'completedUnits',
            'enrollmentCount',
            'enrollmentsWithGrades',
            'enrollmentsWithoutGrades',
            'gradeStats',
            'currentYear'
        ));
    }

    private function calculateGWA($enrollments)
    {
        if ($enrollments->isEmpty()) {
            return null;
        }

        $totalWeightedGrade = 0;
        $totalUnits = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->grade && is_numeric($enrollment->grade) && $enrollment->subject) {
                $units = $enrollment->subject->units ?? 0;
                $totalWeightedGrade += $enrollment->grade * $units;
                $totalUnits += $units;
            }
        }

        if ($totalUnits == 0) {
            return null;
        }

        return round($totalWeightedGrade / $totalUnits, 2);
    }

}
