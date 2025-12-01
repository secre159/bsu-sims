<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Department;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs = Program::with('department')->withCount('students')->get();
        
        return view('programs.index', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('programs.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:programs,code|max:20',
            'name' => 'required|max:255',
'department_id' => 'required|exists:departments,id',
            'description' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $program = Program::create($validated);

        // Log program creation
        Activity::create([
            'user_id' => Auth::id(),
            'subject_type' => 'App\\Models\\Program',
            'subject_id' => $program->id,
            'action' => 'created',
            'description' => "Program {$program->code} - {$program->name} created",
            'properties' => ['code' => $program->code],
        ]);

        return redirect()->route('programs.index')
            ->with('success', 'Program added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program)
    {
        $program->loadCount('students');
        $students = $program->students()->latest()->paginate(15);
        $activeStudents = $program->students()->where('status', 'Active')->count();
        $graduatedStudents = $program->students()->where('status', 'Graduated')->count();
        
        return view('programs.show', compact('program', 'students', 'activeStudents', 'graduatedStudents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('programs.edit', compact('program', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'code' => 'required|max:20|unique:programs,code,' . $program->id,
            'name' => 'required|max:255',
'department_id' => 'required|exists:departments,id',
            'description' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $original = $program->getOriginal();
        $program->update($validated);

        // Log changes
        $changes = [];
        foreach ($validated as $key => $value) {
            if (($original[$key] ?? null) != $value) {
                $changes[$key] = [
                    'old' => $original[$key] ?? null,
                    'new' => $value,
                ];
            }
        }

        if (!empty($changes)) {
            Activity::create([
                'user_id' => Auth::id(),
                'subject_type' => 'App\\Models\\Program',
                'subject_id' => $program->id,
                'action' => 'updated',
                'description' => "Program {$program->code} - {$program->name} updated",
                'properties' => ['changes' => $changes],
            ]);
        }

        return redirect()->route('programs.index')
            ->with('success', 'Program updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        // Check if program has students
        if ($program->students()->count() > 0) {
            return redirect()->route('programs.index')
                ->with('error', 'Cannot delete program with enrolled students!');
        }

        // Log deletion
        Activity::create([
            'user_id' => Auth::id(),
            'subject_type' => 'App\\Models\\Program',
            'subject_id' => $program->id,
            'action' => 'deleted',
            'description' => "Program {$program->code} - {$program->name} deleted",
            'properties' => ['code' => $program->code],
        ]);

        $program->delete();

        return redirect()->route('programs.index')
            ->with('success', 'Program deleted successfully!');
    }
}
