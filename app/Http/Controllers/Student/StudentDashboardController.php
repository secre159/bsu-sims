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
            ->with('subject')
            ->whereIn('status', ['Enrolled', 'Completed'])
            ->when($currentYear, function ($query) use ($currentYear) {
                return $query->where('academic_year_id', $currentYear->id);
            })
            ->get();

        // Get all completed enrollments
        $completedCourses = $student->enrollments()
            ->where('status', 'Completed')
            ->get();

        // Calculate GPA
        $gpa = $this->calculateGPA($completedCourses);

        // Get quick stats
        $totalUnits = $currentEnrollments->sum('subject.units');
        $enrollmentCount = $currentEnrollments->count();

        return view('student.dashboard', compact(
            'student',
            'currentEnrollments',
            'completedCourses',
            'gpa',
            'totalUnits',
            'enrollmentCount'
        ));
    }

    private function calculateGPA($enrollments)
    {
        if ($enrollments->isEmpty()) {
            return 0.0;
        }

        $gradePoints = [
            '1.0' => 4.0,
            '1.25' => 3.75,
            '1.5' => 3.5,
            '1.75' => 3.25,
            '2.0' => 3.0,
            '2.25' => 2.75,
            '2.5' => 2.5,
            '2.75' => 2.25,
            '3.0' => 2.0,
            '4.0' => 0.0,
        ];

        $totalPoints = 0;
        $totalUnits = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->grade && isset($gradePoints[$enrollment->grade])) {
                $points = $gradePoints[$enrollment->grade];
                $units = $enrollment->subject->units ?? 0;
                $totalPoints += $points * $units;
                $totalUnits += $units;
            }
        }

        if ($totalUnits == 0) {
            return 0.0;
        }

        return round($totalPoints / $totalUnits, 2);
    }
}
