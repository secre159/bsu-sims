<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with('department');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Department filter
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $users = $query->orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $roles = ['admin', 'chairperson', 'approver', 'user'];

        return view('users.index', compact('users', 'departments', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $roles = ['admin', 'chairperson', 'approver', 'user'];

        return view('users.create', compact('departments', 'roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,chairperson,approver,user'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        // Chairpersons must have a department
        if ($validated['role'] === 'chairperson' && empty($validated['department_id'])) {
            return back()->withErrors(['department_id' => 'Chairpersons must be assigned to a department.'])->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'department_id' => $validated['department_id'],
        ]);

        return redirect()->route('users.index')->with('success', "User '{$user->name}' created successfully.");
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user)
    {
        $departments = Department::orderBy('name')->get();
        $roles = ['admin', 'chairperson', 'approver', 'user'];

        return view('users.edit', compact('user', 'departments', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,chairperson,approver,user'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        // Chairpersons must have a department
        if ($validated['role'] === 'chairperson' && empty($validated['department_id'])) {
            return back()->withErrors(['department_id' => 'Chairpersons must be assigned to a department.'])->withInput();
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->department_id = $validated['department_id'];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', "User '{$user->name}' updated successfully.");
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('success', "User '{$name}' deleted successfully.");
    }
}
