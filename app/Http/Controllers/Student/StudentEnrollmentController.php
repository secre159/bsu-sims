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
            ->latest()
            ->get()
            ->groupBy(function ($enrollment) {
                return $enrollment->academicYear->year_code . ' - ' . $enrollment->academicYear->semester;
            });

        return view('student.enrollments', compact('student', 'enrollments'));
    }
}
