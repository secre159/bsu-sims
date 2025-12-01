<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\StudentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UnifiedLoginController extends Controller
{
    /**
     * Handle login for both admin and student
     * Detects based on identifier format
     */
    public function store(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $request->identifier;
        $password = $request->password;
        $remember = $request->filled('remember');

        // Check if it's an email (admin) or student ID
        if (strpos($identifier, '@') !== false) {
            // Has @ = admin email login
            return $this->adminLogin($identifier, $password, $remember);
        } else {
            // No @ = student ID login (e.g., 2024-0001)
            return $this->studentLogin($identifier, $password, $remember);
        }
    }

    /**
     * Admin login via email
     */
    private function adminLogin($email, $password, $remember)
    {
        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'identifier' => 'Invalid credentials.',
        ])->onlyInput('identifier');
    }

    /**
     * Student login via student ID
     */
    private function studentLogin($studentId, $password, $remember)
    {
        // Find student by student_id
        $student = Student::where('student_id', $studentId)->first();

        if (!$student) {
            return back()->withErrors([
                'identifier' => 'Student ID not found.',
            ])->onlyInput('identifier');
        }

        // Find StudentUser for this student
        $studentUser = StudentUser::where('student_id', $student->id)->first();

        if (!$studentUser) {
            return back()->withErrors([
                'identifier' => 'No login account found.',
            ])->onlyInput('identifier');
        }

        // Check password
        if (!Hash::check($password, $studentUser->password)) {
            return back()->withErrors([
                'password' => 'Invalid password.',
            ])->onlyInput('identifier');
        }

        // Login student
        Auth::guard('student')->login($studentUser, $remember);
        session()->regenerate();

        return redirect()->intended(route('student.dashboard'));
    }
}
