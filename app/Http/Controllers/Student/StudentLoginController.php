<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find student by student_id string
        $student = \App\Models\Student::where('student_id', $request->student_id)->first();

        if (!$student) {
            return back()->withErrors(['student_id' => 'Student ID not found.'])->onlyInput('student_id');
        }

        // Find StudentUser for this student
        $studentUser = StudentUser::where('student_id', $student->id)->first();

        if (!$studentUser) {
            return back()->withErrors(['student_id' => 'No login account found for this student.'])->onlyInput('student_id');
        }

        // Verify password
        if (!\Illuminate\Support\Facades\Hash::check($request->password, $studentUser->password)) {
            return back()->withErrors([
                'password' => 'The provided password is incorrect.',
            ])->onlyInput('student_id');
        }

        // Login
        Auth::guard('student')->login($studentUser, $request->filled('remember'));
        $request->session()->regenerate();

        // Note: Activity logging requires user_id. For student logins, we skip this.
        // If you want to track student logins, make user_id nullable in activities table.

        return redirect()->intended(route('student.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
