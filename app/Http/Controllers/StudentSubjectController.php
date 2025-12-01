<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSubjectController extends Controller
{
    /**
     * Show subject enrollment page for student
     */
    public function index(Student $student)
    {
        // Get available subjects for student's program and year level
        $availableSubjects = Subject::where('program_id', $student->program_id)
            ->where('year_level', $student->year_level)
            ->where('is_active', true)
            ->with('program')
            ->get();

        // Get student's enrolled subjects with academic year info
        // Only include enrollments where the subject is not deleted
        $enrolledSubjects = $student->enrollments()
            ->with(['subject', 'academicYear'])
            ->whereHas('subject', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current academic year
        $currentAcademicYear = AcademicYear::where('is_current', true)->first();

        return view('students.subjects', compact('student', 'availableSubjects', 'enrolledSubjects', 'currentAcademicYear'));
    }

    /**
     * Enroll student in a subject
     */
    public function enroll(Request $request, Student $student)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
        ]);

        // Get current academic year
        $currentAcademicYear = AcademicYear::where('is_current', true)->first();
        if (!$currentAcademicYear) {
            return back()->with('error', 'No active academic year set');
        }

        // Get the subject
        $subject = Subject::findOrFail($validated['subject_id']);

        // VALIDATION 1: Check student status - only Active students can enroll
        if ($student->status !== 'Active') {
            return back()->with('error', "Cannot enroll: Student status is '{$student->status}'. Only Active students can register.");
        }

        // VALIDATION 2: Check subject is active
        if (!$subject->is_active) {
            return back()->with('error', "Cannot enroll: {$subject->name} is no longer offered (dropped from curriculum).");
        }

        // VALIDATION 3: Check program matching - student must be in same program
        if ($subject->program_id !== $student->program_id) {
            return back()->with('error', "Cannot enroll: {$subject->name} is not offered in {$student->program->code} program.");
        }

        // VALIDATION 4: Check year level - cannot take courses above current year level
        $yearLevelOrder = ['1st Year' => 1, '2nd Year' => 2, '3rd Year' => 3, '4th Year' => 4, '5th Year' => 5];
        $studentLevel = $yearLevelOrder[$student->year_level] ?? 0;
        $subjectLevel = $yearLevelOrder[$subject->year_level] ?? 0;
        
        if ($subjectLevel > $studentLevel) {
            return back()->with('error', "Cannot enroll: {$subject->name} is for {$subject->year_level} students. You are {$student->year_level}.");
        }

        // VALIDATION 5: Check prerequisites - student must have completed prerequisite courses
        if ($subject->prerequisite_subject_ids && count($subject->prerequisite_subject_ids) > 0) {
            $missedPrereqs = [];
            
            foreach ($subject->prerequisite_subject_ids as $prereqId) {
                $completed = Enrollment::where('student_id', $student->id)
                    ->where('subject_id', $prereqId)
                    ->where('status', 'Completed') // Must be completed
                    ->exists();
                
                if (!$completed) {
                    $prereqSubject = Subject::find($prereqId);
                    if ($prereqSubject) {
                        $missedPrereqs[] = $prereqSubject->code . ' - ' . $prereqSubject->name;
                    }
                }
            }
            
            if (!empty($missedPrereqs)) {
                $prereqList = implode(', ', $missedPrereqs);
                return back()->with('error', "Cannot enroll: Must complete the following prerequisite course(s): {$prereqList}");
            }
        }

        // VALIDATION 6: Check for duplicate enrollment in current academic year
        $exists = Enrollment::where('student_id', $student->id)
            ->where('subject_id', $validated['subject_id'])
            ->where('academic_year_id', $currentAcademicYear->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Student is already enrolled in this subject for the current academic year!');
        }

        // VALIDATION 7: No credit hour limit - students can enroll in as many units as needed

        // VALIDATION 8: Check registration period (if dates are set)
        if ($currentAcademicYear->registration_start_date && $currentAcademicYear->registration_end_date) {
            $today = now()->toDateString();
            if ($today < $currentAcademicYear->registration_start_date->toDateString()) {
                return back()->with('error', 'Registration period has not yet opened.');
            }
            if ($today > $currentAcademicYear->registration_end_date->toDateString()) {
                return back()->with('error', 'Registration period has closed. Contact the Registrar for late registration.');
            }
        }

        // All validations passed - create enrollment
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $validated['subject_id'],
            'academic_year_id' => $currentAcademicYear->id,
            'status' => 'Enrolled',
        ]);

        // Log enrollment action
        Activity::create([
            'user_id' => Auth::id(),
            'subject_type' => 'App\\Models\\Enrollment',
            'subject_id' => $enrollment->id,
            'action' => 'enrolled',
            'description' => "{$student->first_name} {$student->last_name} enrolled in {$subject->code} - {$subject->name}",
            'properties' => [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'units' => $subject->units,
            ],
        ]);

        return back()->with('success', 'Student enrolled in subject successfully!');
    }

    /**
     * Drop/remove student from a subject
     */
    public function drop(Student $student, Enrollment $enrollment)
    {
        if ($enrollment->student_id !== $student->id) {
            return back()->with('error', 'Invalid enrollment!');
        }

        // If add/drop deadline is configured, enforce it
        $currentAcademicYear = AcademicYear::where('is_current', true)->first();
        if ($currentAcademicYear && $currentAcademicYear->add_drop_deadline) {
            if (now()->toDateString() > $currentAcademicYear->add_drop_deadline->toDateString()) {
                return back()->with('error', 'Add/Drop deadline has passed. Contact the Registrar.');
            }
        }

        // Get subject info before marking dropped (for logging)
        $subject = $enrollment->subject;

        // Instead of deleting, mark as Dropped to preserve history
        $enrollment->status = 'Dropped';
        $enrollment->remarks = 'Dropped on ' . now()->format('Y-m-d H:i');
        $enrollment->save();

        // Log the drop action
        Activity::create([
            'user_id' => Auth::id(),
            'subject_type' => 'App\\Models\\Enrollment',
            'subject_id' => $enrollment->id,
            'action' => 'dropped',
            'description' => "{$student->first_name} {$student->last_name} dropped {$subject->code} - {$subject->name}",
            'properties' => [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'units' => $subject->units,
                'dropped_at' => now()->format('Y-m-d H:i:s'),
            ],
        ]);

        return back()->with('success', 'Subject dropped successfully!');
    }
}
