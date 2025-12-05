<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentEnrollmentController extends Controller
{
    public function index()
    {
        $studentUser = Auth::guard('student')->user();
        $student = $studentUser->student;

        // Group enrollments by semester/academic year
        $enrollments = $student->enrollments()
            ->with(['subject', 'academicYear'])
            ->get();

        // Sort by academic year code descending (newest first)
        $enrollments = $enrollments->sortByDesc(function ($enrollment) {
            return $enrollment->academicYear->year_code ?? '';
        });

        // Group by semester with year level
        $enrollments = $enrollments->groupBy(function ($enrollment) {
            if ($enrollment->academicYear && $enrollment->subject) {
                $yearLevel = $enrollment->subject->year_level;
                return $enrollment->academicYear->year_code . ' - ' . $enrollment->academicYear->semester . ' (' . $yearLevel . ')';
            }
            return 'No Academic Year';
        });

        return view('student.enrollments', compact('student', 'enrollments'));
    }
}
