<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $academicYears = AcademicYear::orderBy('year_code', 'desc')->paginate(10);
        return view('academic-years.index', compact('academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('academic-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year_code' => 'required|max:20|unique:academic_years,year_code',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_start_date' => 'nullable|date',
            'registration_end_date' => 'nullable|date|after_or_equal:registration_start_date',
            'add_drop_deadline' => 'nullable|date',
            'classes_start_date' => 'nullable|date',
            'classes_end_date' => 'nullable|date|after_or_equal:classes_start_date',
            'midterm_start_date' => 'nullable|date',
            'midterm_end_date' => 'nullable|date|after_or_equal:midterm_start_date',
            'exam_start_date' => 'nullable|date',
            'exam_end_date' => 'nullable|date|after_or_equal:exam_start_date',
        ]);

        // Create both 1st and 2nd semester
        $semesters = ['1st Semester', '2nd Semester'];
        $createdYears = [];

        foreach ($semesters as $semester) {
            $data = array_merge($validated, ['semester' => $semester]);
            $academicYear = AcademicYear::create($data);
            $createdYears[] = $academicYear;

            // Log each semester creation
            Activity::create([
                'user_id' => Auth::id(),
                'subject_type' => 'App\\Models\\AcademicYear',
                'subject_id' => $academicYear->id,
                'action' => 'created',
                'description' => "Academic Year {$academicYear->year_code} - {$academicYear->semester} created",
                'properties' => [
                    'year_code' => $academicYear->year_code,
                    'semester' => $academicYear->semester,
                ],
            ]);
        }

        return redirect()->route('academic-years.index')
            ->with('success', "Academic year {$validated['year_code']} created successfully! Both 1st and 2nd semesters have been created.");
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        return view('academic-years.show', compact('academicYear'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear)
    {
        return view('academic-years.edit', compact('academicYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'year_code' => 'required|max:20|unique:academic_years,year_code,' . $academicYear->id,
            'semester' => 'required|in:1st Semester,2nd Semester,Summer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_start_date' => 'nullable|date',
            'registration_end_date' => 'nullable|date|after_or_equal:registration_start_date',
            'add_drop_deadline' => 'nullable|date',
            'classes_start_date' => 'nullable|date',
            'classes_end_date' => 'nullable|date|after_or_equal:classes_start_date',
            'midterm_start_date' => 'nullable|date',
            'midterm_end_date' => 'nullable|date|after_or_equal:midterm_start_date',
            'exam_start_date' => 'nullable|date',
            'exam_end_date' => 'nullable|date|after_or_equal:exam_start_date',
        ]);

        $original = $academicYear->getOriginal();
        $academicYear->update($validated);

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
                'subject_type' => 'App\\Models\\AcademicYear',
                'subject_id' => $academicYear->id,
                'action' => 'updated',
                'description' => "Academic Year {$academicYear->year_code} updated",
                'properties' => ['changes' => $changes],
            ]);
        }

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic year updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        // Log deletion
        Activity::create([
            'user_id' => Auth::id(),
            'subject_type' => 'App\\Models\\AcademicYear',
            'subject_id' => $academicYear->id,
            'action' => 'deleted',
            'description' => "Academic Year {$academicYear->year_code} - {$academicYear->semester} deleted",
            'properties' => [
                'year_code' => $academicYear->year_code,
                'semester' => $academicYear->semester,
            ],
        ]);

        $academicYear->delete();

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic year deleted successfully!');
    }

    /**
     * Set the academic year as current.
     */
    public function setCurrent(AcademicYear $academicYear)
    {
        // Set all to false first
        AcademicYear::query()->update(['is_current' => false]);
        // Set the selected one to true
        $academicYear->update(['is_current' => true]);

        // Log the change
        Activity::create([
            'user_id' => Auth::id(),
            'subject_type' => 'App\\Models\\AcademicYear',
            'subject_id' => $academicYear->id,
            'action' => 'set_current',
            'description' => "Academic Year {$academicYear->year_code} - {$academicYear->semester} set as current",
            'properties' => [
                'year_code' => $academicYear->year_code,
                'semester' => $academicYear->semester,
            ],
        ]);

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic year set as current successfully!');
    }
}
