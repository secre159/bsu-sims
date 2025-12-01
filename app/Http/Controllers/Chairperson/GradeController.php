<?php

namespace App\Http\Controllers\Chairperson;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\GradeHistory;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display hybrid view with students and subjects tabs
     */
    public function index(Request $request)
    {
        $chairperson = auth()->user();
        $view = $request->get('view', 'students'); // Default to students view
        
        // Get subjects in chairperson's department
        $departmentSubjects = Subject::where('department_id', $chairperson->department_id)->pluck('id');
        
        // Get unique students with enrollments in department's subjects
        $students = Student::whereHas('enrollments', function ($query) use ($departmentSubjects) {
            $query->whereIn('subject_id', $departmentSubjects);
        })
        ->withCount(['enrollments as total_subjects' => function ($query) use ($departmentSubjects) {
            $query->whereIn('subject_id', $departmentSubjects);
        }])
        ->withCount(['enrollments as graded_subjects' => function ($query) use ($departmentSubjects) {
            $query->whereIn('subject_id', $departmentSubjects)->whereNotNull('grade');
        }])
        ->with(['program'])
        ->orderBy('last_name')
        ->get();
        
        // Get subjects with enrollment counts
        $subjects = Subject::where('department_id', $chairperson->department_id)
            ->withCount('enrollments')
            ->withCount(['enrollments as graded_count' => function ($query) {
                $query->whereNotNull('grade');
            }])
            ->orderBy('code')
            ->get();

        return view('chairperson.grades.index', compact('students', 'subjects', 'view'));
    }
    
    /**
     * Show student's enrollments for grade entry
     */
    public function showStudent(Student $student)
    {
        $chairperson = auth()->user();
        
        // Get enrollments for subjects in chairperson's department
        $enrollments = $student->enrollments()
            ->whereHas('subject', function ($query) use ($chairperson) {
                $query->where('department_id', $chairperson->department_id);
            })
            ->with(['subject', 'academicYear'])
            ->get();
        
        if ($enrollments->isEmpty()) {
            abort(403, 'This student has no enrollments in your department.');
        }

        return view('chairperson.grades.student', compact('student', 'enrollments'));
    }
    
    /**
     * Show subject's enrollments for bulk grade entry
     */
    public function showSubject(Subject $subject)
    {
        $chairperson = auth()->user();
        
        // Verify subject belongs to chairperson's department
        if ($subject->department_id !== $chairperson->department_id) {
            abort(403, 'You cannot access this subject.');
        }
        
        $enrollments = $subject->enrollments()
            ->with(['student', 'academicYear'])
            ->get();

        return view('chairperson.grades.subject', compact('subject', 'enrollments'));
    }
    
    /**
     * Bulk update grades for a subject
     */
    public function bulkUpdate(Request $request, Subject $subject)
    {
        $chairperson = auth()->user();
        
        if ($subject->department_id !== $chairperson->department_id) {
            abort(403, 'You cannot modify grades for this subject.');
        }
        
        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*.enrollment_id' => 'required|exists:enrollments,id',
            'grades.*.grade' => 'nullable|string|in:1.00,1.25,1.50,1.75,2.00,2.25,2.50,2.75,3.00,5.00,IP,INC',
            'reason' => 'required|string',
        ]);
        
        $updatedCount = 0;
        
        foreach ($validated['grades'] as $gradeData) {
            $enrollment = Enrollment::find($gradeData['enrollment_id']);
            
            // Skip if no grade provided or grade unchanged
            if ($gradeData['grade'] === null || $gradeData['grade'] === '') {
                continue;
            }
            
            $oldGrade = $enrollment->grade;
            $newGrade = $gradeData['grade'];
            
            // Skip if grade hasn't changed
            if ($oldGrade == $newGrade) {
                continue;
            }
            
            $enrollment->update([
                'grade' => $newGrade,
                'submission_date' => now(),
            ]);
            
            GradeHistory::create([
                'enrollment_id' => $enrollment->id,
                'user_id' => $chairperson->id,
                'old_grade' => $oldGrade,
                'new_grade' => $newGrade,
                'reason' => $validated['reason'],
            ]);
            
            $updatedCount++;
        }
        
        return redirect()->route('chairperson.grades.subject', $subject)
            ->with('success', "{$updatedCount} grade(s) updated successfully.");
    }

    /**
     * Show form to enter grade for a specific enrollment
     */
    public function edit(Enrollment $enrollment)
    {
        $chairperson = auth()->user();
        
        // Verify subject belongs to chairperson's department
        if ($enrollment->subject->department_id !== $chairperson->department_id) {
            abort(403, 'You cannot modify grades for this subject.');
        }

        return view('chairperson.grades.edit', compact('enrollment'));
    }

    /**
     * Store grade for enrollment
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $chairperson = auth()->user();
        
        // Verify subject belongs to chairperson's department
        if ($enrollment->subject->department_id !== $chairperson->department_id) {
            abort(403, 'You cannot modify grades for this subject.');
        }

        $validated = $request->validate([
            'grade' => 'required|string|in:1.00,1.25,1.50,1.75,2.00,2.25,2.50,2.75,3.00,5.00,IP,INC',
            'remarks' => 'nullable|string',
            'reason' => 'required|string',
        ]);

        // Store old grade for history
        $oldGrade = $enrollment->grade;
        $newGrade = $validated['grade'];

        // Update enrollment
        $enrollment->update([
            'grade' => $newGrade,
            'remarks' => $validated['remarks'] ?? null,
            'submission_date' => now(),
        ]);

        // Record grade history
        GradeHistory::create([
            'enrollment_id' => $enrollment->id,
            'user_id' => $chairperson->id,
            'old_grade' => $oldGrade,
            'new_grade' => $newGrade,
            'reason' => $validated['reason'],
        ]);

        return redirect()->route('chairperson.grades.index')
            ->with('success', 'Grade recorded successfully.');
    }

    /**
     * Show grade history for an enrollment
     */
    public function history(Enrollment $enrollment)
    {
        $chairperson = auth()->user();
        
        if ($enrollment->subject->department_id !== $chairperson->department_id) {
            abort(403, 'You cannot view history for this subject.');
        }

        $histories = $enrollment->gradeHistories()->with('user')->get();

        return view('chairperson.grades.history', compact('enrollment', 'histories'));
    }
}
