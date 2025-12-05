<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\GradeImportBatch;
use App\Services\GwaCalculationService;
use Illuminate\Http\Request;

class AdminGradeApprovalController extends Controller
{
    private GwaCalculationService $gwaService;

    public function __construct(GwaCalculationService $gwaService)
    {
        $this->gwaService = $gwaService;
    }

    /**
     * Display all pending grade import batches
     */
    public function index()
    {
        $batches = GradeImportBatch::where('status', 'submitted')
            ->with('chairperson', 'gradeImportRecords')
            ->latest('submitted_at')
            ->paginate(15);

        return view('admin.grade-approvals.index', compact('batches'));
    }

    /**
     * Show details of a specific batch for review
     */
    public function show(GradeImportBatch $batch)
    {
        if ($batch->status !== 'submitted' && $batch->status !== 'approved' && $batch->status !== 'rejected') {
            abort(404, 'Batch not found or not ready for review.');
        }

        $records = $batch->gradeImportRecords()->get();
        $successCount = $records->where('status', 'matched')->count();
        $errorCount = $records->where('status', 'error')->count();

        return view('admin.grade-approvals.show', compact('batch', 'records', 'successCount', 'errorCount'));
    }

    /**
     * Approve a grade import batch
     */
    public function approve(GradeImportBatch $batch)
    {
        if ($batch->status !== 'submitted') {
            return back()->with('error', 'Only submitted batches can be approved.');
        }

        if ($batch->error_count > 0) {
            return back()->with('error', 'Cannot approve batches with errors.');
        }

        try {
            // Get all matched records
            $matchedRecords = $batch->gradeImportRecords()
                ->where('status', 'matched')
                ->get();

            if ($matchedRecords->isEmpty()) {
                return back()->with('error', 'No matched records to approve.');
            }

            // Apply grades to enrollments
            foreach ($matchedRecords as $record) {
                if ($record->enrollment) {
                    $oldGrade = $record->enrollment->grade;

                    // Determine enrollment status based on grade
                    $status = 'Completed';
                    if (is_numeric($record->grade)) {
                        $status = (float)$record->grade >= 4.0 ? 'Failed' : 'Completed';
                    } elseif (in_array($record->grade, ['IP', 'INC'])) {
                        $status = 'Enrolled'; // Still in progress
                    }

                    // Update enrollment
                    $record->enrollment->update([
                        'grade' => $record->grade,
                        'status' => $status,
                        'submission_date' => now(),
                        'approver_id' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    // Create grade history
                    $record->enrollment->gradeHistories()->create([
                        'user_id' => auth()->id(),
                        'old_grade' => $oldGrade,
                        'new_grade' => $record->grade,
                        'reason' => 'Admin Approved: Excel Import Batch',
                    ]);
                }
            }

            // Get affected students and calculate GWAs
            $enrollmentIds = $matchedRecords->pluck('enrollment_id')->toArray();
            $students = $this->gwaService->getAffectedStudents($enrollmentIds);
            $this->gwaService->calculateBatchGwa($students);

            // Update batch status
            $batch->update([
                'status' => 'approved',
                'submitted_at' => now(),
            ]);

            // Log batch approval activity
            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => GradeImportBatch::class,
                'subject_id' => $batch->id,
                'action' => 'grade_batch_approved',
                'description' => "Admin " . auth()->user()->name . " approved grade import batch '{$batch->file_name}' and applied {$matchedRecords->count()} grades",
                'properties' => [
                    'batch_id' => $batch->id,
                    'file_name' => $batch->file_name,
                    'chairperson_id' => $batch->chairperson_id,
                    'grades_applied' => $matchedRecords->count(),
                    'students_affected' => $students->count(),
                ],
            ]);

            return redirect()->route('admin.grade-approvals.show', $batch)
                ->with('success', 'Batch approved successfully. Grades have been applied and GWAs calculated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error approving batch: ' . $e->getMessage());
        }
    }

    /**
     * Reject a grade import batch
     */
    public function reject(Request $request, GradeImportBatch $batch)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if ($batch->status !== 'submitted') {
            return back()->with('error', 'Only submitted batches can be rejected.');
        }

        try {
            $batch->update([
                'status' => 'rejected',
                'submitted_at' => now(),
            ]);

            // Log batch rejection activity
            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => GradeImportBatch::class,
                'subject_id' => $batch->id,
                'action' => 'grade_batch_rejected',
                'description' => "Admin " . auth()->user()->name . " rejected grade import batch '{$batch->file_name}'",
                'properties' => [
                    'batch_id' => $batch->id,
                    'file_name' => $batch->file_name,
                    'chairperson_id' => $batch->chairperson_id,
                    'reason' => $validated['reason'],
                ],
            ]);

            return redirect()->route('admin.grade-approvals.index')
                ->with('success', 'Batch rejected. Chairperson has been notified.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error rejecting batch: ' . $e->getMessage());
        }
    }
}
