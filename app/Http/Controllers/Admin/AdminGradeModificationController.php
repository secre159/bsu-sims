<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\GradeHistory;
use App\Services\GwaCalculationService;
use Illuminate\Http\Request;

class AdminGradeModificationController extends Controller
{
    private GwaCalculationService $gwaService;

    public function __construct(GwaCalculationService $gwaService)
    {
        $this->gwaService = $gwaService;
    }

    /**
     * Show form to edit an approved grade
     */
    public function edit(Enrollment $enrollment)
    {
        if (!$enrollment->grade) {
            return back()->with('error', 'This enrollment does not have a grade to modify.');
        }

        $histories = $enrollment->gradeHistories()->with('user')->latest()->get();

        return view('admin.grade-modifications.edit', compact('enrollment', 'histories'));
    }

    /**
     * Update a grade with audit trail
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
            'reason' => 'required|string|max:500',
        ]);

        if (!$enrollment->grade) {
            return back()->with('error', 'This enrollment does not have a grade to modify.');
        }

        try {
            $oldGrade = $enrollment->grade;
            $newGrade = $validated['grade'];

            // Don't update if grade hasn't changed
            if ($oldGrade == $newGrade) {
                return back()->with('info', 'Grade unchanged. No modification recorded.');
            }

            // Determine enrollment status based on grade
            $status = $enrollment->status;
            if (is_numeric($newGrade)) {
                $status = (float)$newGrade >= 4.0 ? 'Failed' : 'Completed';
            } elseif (in_array($newGrade, ['IP', 'INC'])) {
                $status = 'Enrolled'; // Still in progress
            }

            // Update enrollment
            $enrollment->update([
                'grade' => $newGrade,
                'status' => $status,
                'updated_at' => now(),
            ]);

            // Create grade history record
            GradeHistory::create([
                'enrollment_id' => $enrollment->id,
                'user_id' => auth()->id(),
                'old_grade' => $oldGrade,
                'new_grade' => $newGrade,
                'reason' => 'Admin Modified: ' . $validated['reason'],
            ]);

            // Recalculate student GWA
            $this->gwaService->updateStudentStanding($enrollment->student);

            return redirect()->route('admin.grade-modifications.edit', $enrollment)
                ->with('success', "Grade updated from {$oldGrade} to {$newGrade}. Student GWA recalculated.");
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating grade: ' . $e->getMessage());
        }
    }

    /**
     * View detailed grade history and audit trail
     */
    public function history(Enrollment $enrollment)
    {
        $histories = $enrollment->gradeHistories()
            ->with('user')
            ->latest()
            ->get();

        return view('admin.grade-modifications.history', compact('enrollment', 'histories'));
    }

    /**
     * Search and filter grades for modification
     */
    public function index(Request $request)
    {
        $query = Enrollment::whereNotNull('grade')
            ->with('student', 'subject', 'academicYear')
            ->latest('approved_at');

        // Filter by student search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('approved_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('approved_at', '<=', $request->input('to_date'));
        }

        $enrollments = $query->paginate(20);

        return view('admin.grade-modifications.index', compact('enrollments'));
    }
}
