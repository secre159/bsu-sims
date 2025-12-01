<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    public function show()
    {
        $studentUser = Auth::guard('student')->user();
        $student = $studentUser->student;
        $student->load('program');

        return view('student.profile', compact('student'));
    }
}
