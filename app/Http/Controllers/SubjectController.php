<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Program;
use App\Models\Department;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Load departments with their programs and subjects (only active in hierarchy)
        $departments = Department::where('is_active', true)
            ->with(['programs' => function($query) {
                $query->where('is_active', true)->with('subjects')->orderBy('name');
            }])
            ->get()
            ->map(function($dept) {
                $dept->programs = $dept->programs->map(function($prog) {
                    $prog->subjects = $prog->subjects->where('is_active', true)->sortBy('code');
                    return $prog;
                });
                return $dept;
            });

        // Keep flat list with ALL subjects (active and archived) for stats and modal
        $subjects = Subject::with('program', 'department')->latest()->get();
        $programs = Program::where('is_active', true)->get();

        return view('subjects.index', compact('departments', 'subjects', 'programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = Program::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $subjects = Subject::orderBy('year_level')->orderBy('code')->get();
        return view('subjects.create', compact('programs', 'departments', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:subjects,code|max:20',
            'name' => 'required|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable',
            'units' => 'required|integer|min:1|max:10',
            'program_id' => 'required|exists:programs,id',
'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year',
            'semester' => 'required|in:1st Semester,2nd Semester,Summer',
            'is_active' => 'boolean',
            'prerequisite_subject_ids' => 'nullable|array',
            'prerequisite_subject_ids.*' => 'exists:subjects,id',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['prerequisite_subject_ids'] = $request->input('prerequisite_subject_ids') ?? [];

        // Ensure department matches the program's department
        $programDeptId = Program::where('id', $validated['program_id'])->value('department_id');
        $validated['department_id'] = $programDeptId;

        $subject = Subject::create($validated);

        // Log subject creation
        Activity::create([
            'user_id' => Auth::id(),
            'subject_type' => 'App\\Models\\Subject',
            'subject_id' => $subject->id,
            'action' => 'created',
            'description' => "Subject {$subject->code} - {$subject->name} created",
            'properties' => [
                'code' => $subject->code,
                'program_id' => $subject->program_id,
                'year_level' => $subject->year_level,
                'units' => $subject->units,
            ],
        ]);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        $subject->load('program');
        return view('subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $programs = Program::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $subjects = Subject::where('id', '!=', $subject->id)->orderBy('year_level')->orderBy('code')->get();
        return view('subjects.edit', compact('subject', 'programs', 'departments', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code' => 'required|max:20|unique:subjects,code,' . $subject->id,
            'name' => 'required|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable',
            'units' => 'required|integer|min:1|max:10',
            'program_id' => 'required|exists:programs,id',
'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year',
            'semester' => 'required|in:1st Semester,2nd Semester,Summer',
            'is_active' => 'boolean',
            'prerequisite_subject_ids' => 'nullable|array',
            'prerequisite_subject_ids.*' => 'exists:subjects,id',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['prerequisite_subject_ids'] = $request->input('prerequisite_subject_ids') ?? [];

        // Ensure department matches the program's department
        $programDeptId = Program::where('id', $validated['program_id'])->value('department_id');
        $validated['department_id'] = $programDeptId;

        $original = $subject->getOriginal();
        $subject->update($validated);

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
                'subject_type' => 'App\\Models\\Subject',
                'subject_id' => $subject->id,
                'action' => 'updated',
                'description' => "Subject {$subject->code} - {$subject->name} updated",
                'properties' => ['changes' => $changes],
            ]);
        }

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully!');
    }

    /**
     * Toggle archive status (soft archive/restore).
     */
    public function destroy(Subject $subject)
    {
        // Toggle is_active status
        $newStatus = !$subject->is_active;
        $action = $newStatus ? 'restored' : 'archived';
        
        $subject->update(['is_active' => $newStatus]);

        // Log archive/restore action
        Activity::create([
            'user_id' => Auth::id(),
            'subject_type' => 'App\\Models\\Subject',
            'subject_id' => $subject->id,
            'action' => $action,
            'description' => "Subject {$subject->code} - {$subject->name} {$action}",
            'properties' => [
                'code' => $subject->code,
                'program_id' => $subject->program_id,
                'is_active' => $newStatus,
            ],
        ]);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject ' . $action . ' successfully!');
    }
}
