<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentUser;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
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
            // Admin login
            if (Auth::attempt(['email' => $identifier, 'password' => $password], $remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard', absolute: false));
            }
            return back()->withErrors(['identifier' => 'Invalid credentials.'])->onlyInput('identifier');
        } else {
            // Student login - try direct email auth first
            $studentUser = StudentUser::where('email', $identifier)->first();
            
            if ($studentUser && Hash::check($password, $studentUser->password)) {
                Auth::guard('student')->login($studentUser, $remember);
                $request->session()->regenerate();
                return redirect('/student/dashboard');
            }
            
            // Then try by student_id
            $student = Student::where('student_id', $identifier)->first();

            if (!$student) {
                return back()->withErrors(['identifier' => 'Student ID not found.'])->onlyInput('identifier');
            }

            $studentUser = StudentUser::where('student_id', $student->id)->first();

            if (!$studentUser) {
                return back()->withErrors(['identifier' => 'No login account found.'])->onlyInput('identifier');
            }

            if (!Hash::check($password, $studentUser->password)) {
                return back()->withErrors(['password' => 'Invalid password.'])->onlyInput('identifier');
            }

            Auth::guard('student')->login($studentUser, $remember);
            $request->session()->regenerate();
            return redirect('/student/dashboard');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
