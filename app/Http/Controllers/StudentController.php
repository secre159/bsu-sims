<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Program;
use App\Models\Department;
use App\Models\AcademicYear;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Load all students for client-side filtering
        $students = Student::with('program.department')->latest()->get();
        $programs = Program::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('students.index', compact('students', 'programs', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = Program::where('is_active', true)->with('department')->get();
        $academicYears = AcademicYear::all();
        
        return view('students.create', compact('programs', 'academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|unique:students,student_id|max:20',
            'last_name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'middle_name' => 'nullable|max:255',
            'suffix' => 'nullable|max:10',
            'maiden_name' => 'nullable|max:255',
            'student_type' => 'nullable|in:Continuing,New/Returner,Candidate for graduation',
            'birthdate' => 'required|date',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|max:255',
            'citizenship' => 'nullable|max:255',
            'ethnicity_tribal_affiliation' => 'nullable|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'contact_number' => 'nullable|max:20',
            'email_address' => 'nullable|email|max:255',
            'home_address' => 'nullable',
            'address_while_studying' => 'nullable',
            'address' => 'nullable',
            'mother_name' => 'nullable|max:255',
            'mother_contact_number' => 'nullable|max:20',
            'father_name' => 'nullable|max:255',
            'father_contact_number' => 'nullable|max:20',
            'emergency_contact_person' => 'nullable|max:255',
            'emergency_contact_relationship' => 'nullable|max:255',
            'emergency_contact_number' => 'nullable|max:20',
            'program_id' => 'required|exists:programs,id',
            'degree' => 'nullable|max:255',
            'major' => 'nullable|max:255',
            'section' => 'nullable|max:50',
'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year',
            'status' => 'required|in:Active,Graduated,Dropped,On Leave,Transferred',
            'attendance_type' => 'nullable|in:regular,irregular',
            'curriculum_used' => 'nullable|max:255',
            'total_units_enrolled' => 'nullable|integer|min:0',
            'free_higher_education_benefit' => 'nullable|in:yes,yes_with_contribution,no_optout,not_qualified',
            'enrollment_date' => 'nullable|date',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'notes' => 'nullable',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Validate logical status/year level combinations
        $this->validateStatusYearLevel($request->year_level, $request->status);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo_path'] = $path;
        }

        $student = Student::create($validated);

        // Log student creation
        Activity::create([
            'user_id' => Auth::id(),
            'subject_type' => 'App\\Models\\Student',
            'subject_id' => $student->id,
            'action' => 'created',
            'description' => "Student {$student->first_name} {$student->last_name} (ID: {$student->student_id}) created",
            'properties' => [
                'student_id' => $student->student_id,
                'program_id' => $student->program_id,
            ],
        ]);

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load('program', 'academicYear');
        
        // Load enrolled subjects grouped by year level and semester
        // Only include enrollments where the subject is not deleted
        $enrolledSubjects = $student->enrollments()
            ->with(['subject', 'academicYear'])
            ->whereHas('subject', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('subject.year_level');
        
        return view('students.show', compact('student', 'enrolledSubjects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $programs = Program::where('is_active', true)->with('department')->get();
        $academicYears = AcademicYear::all();
        
        return view('students.edit', compact('student', 'programs', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_id' => 'required|max:20|unique:students,student_id,' . $student->id,
            'last_name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'middle_name' => 'nullable|max:255',
            'suffix' => 'nullable|max:10',
            'maiden_name' => 'nullable|max:255',
            'student_type' => 'nullable|in:Continuing,New/Returner,Candidate for graduation',
            'birthdate' => 'required|date',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|max:255',
            'citizenship' => 'nullable|max:255',
            'ethnicity_tribal_affiliation' => 'nullable|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'contact_number' => 'nullable|max:20',
            'email_address' => 'nullable|email|max:255',
            'home_address' => 'nullable',
            'address_while_studying' => 'nullable',
            'address' => 'nullable',
            'mother_name' => 'nullable|max:255',
            'mother_contact_number' => 'nullable|max:20',
            'father_name' => 'nullable|max:255',
            'father_contact_number' => 'nullable|max:20',
            'emergency_contact_person' => 'nullable|max:255',
            'emergency_contact_relationship' => 'nullable|max:255',
            'emergency_contact_number' => 'nullable|max:20',
            'program_id' => 'required|exists:programs,id',
            'degree' => 'nullable|max:255',
            'major' => 'nullable|max:255',
            'section' => 'nullable|max:50',
'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year',
            'status' => 'required|in:Active,Graduated,Dropped,On Leave,Transferred',
            'attendance_type' => 'nullable|in:regular,irregular',
            'curriculum_used' => 'nullable|max:255',
            'total_units_enrolled' => 'nullable|integer|min:0',
            'free_higher_education_benefit' => 'nullable|in:yes,yes_with_contribution,no_optout,not_qualified',
            'enrollment_date' => 'nullable|date',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'notes' => 'nullable',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Validate logical status/year level combinations
        $this->validateStatusYearLevel($request->year_level, $request->status);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo_path'] = $path;
        }

        // Capture original values for change log
        $original = $student->getOriginal();

        $student->update($validated);

        // Compute changes
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
                'subject_type' => 'App\\Models\\Student',
                'subject_id' => $student->id,
                'action' => 'updated',
                'description' => "Student {$student->first_name} {$student->last_name} updated",
                'properties' => [
                    'changes' => $changes,
                ],
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Mark student as dropped/withdrawn.
     */
    public function destroy(Student $student)
    {
        // Store original status
        $originalStatus = $student->status;
        
        // Update status to Dropped
        $student->update(['status' => 'Dropped']);

        // Log the status change
        Activity::create([
            'user_id' => Auth::id(),
            'subject_type' => 'App\\Models\\Student',
            'subject_id' => $student->id,
            'action' => 'status_changed',
            'description' => "Student {$student->first_name} {$student->last_name} marked as Dropped",
            'properties' => [
                'student_id' => $student->student_id,
                'old_status' => $originalStatus,
                'new_status' => 'Dropped',
            ],
        ]);

        return redirect()->route('students.index')
            ->with('success', 'Student marked as Dropped successfully!');
    }

    /**
     * Show student history.
     */
    public function history(Student $student)
    {
        $history = $student->history()->with('user')->latest()->get();
        
        return view('students.history', compact('student', 'history'));
    }

    /**
     * Generate ID card for student.
     */
    public function generateIdCard(Student $student)
    {
        $student->load('program');
        
        $pdf = Pdf::loadView('students.id-card', compact('student'))
            ->setPaper([0, 0, 243, 153], 'portrait'); // ID card size: 85.6mm x 53.98mm
        
        return $pdf->download('ID-Card-' . $student->student_id . '.pdf');
    }

    /**
     * Validate logical status/year level combinations
     * A student cannot be Graduated if they are in 1st or 2nd year
     */
    private function validateStatusYearLevel($yearLevel, $status)
    {
        $yearLevelOrder = [
            '1st Year' => 1,
            '2nd Year' => 2,
            '3rd Year' => 3,
            '4th Year' => 4,
            '5th Year' => 5,
        ];

        $level = $yearLevelOrder[$yearLevel] ?? 0;

        // Graduated students should be in 4th or 5th year (or higher)
        if ($status === 'Graduated' && $level < 4) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'status' => "Cannot mark {$yearLevel} student as Graduated. Only 4th+ year students can graduate."
            ]);
        }
    }
}
