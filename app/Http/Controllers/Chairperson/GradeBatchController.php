<?php

namespace App\Http\Controllers\Chairperson;

use App\Http\Controllers\Controller;
use App\Models\GradeImportBatch;
use Illuminate\Http\Request;

class GradeBatchController extends Controller
{
    /**
     * Display all import batches for chairperson
     */
    public function index()
    {
        $chairperson = auth()->user();
        $batches = $chairperson->gradeImportBatches()
            ->with('gradeImportRecords')
            ->latest()
            ->paginate(15);

        return view('chairperson.grades.batches.index', compact('batches'));
    }

    /**
     * Show details of a specific batch
     */
    public function show(GradeImportBatch $batch)
    {
        $chairperson = auth()->user();
        
        if ($batch->chairperson_id !== $chairperson->id) {
            abort(403, 'You cannot view this batch.');
        }

        $records = $batch->gradeImportRecords()->get();
        $successCount = $records->where('status', 'matched')->count();
        $errorCount = $records->where('status', 'error')->count();

        return view('chairperson.grades.batches.show', compact('batch', 'records', 'successCount', 'errorCount'));
    }

    /**
     * Retry failed records in batch
     */
    public function retry(GradeImportBatch $batch)
    {
        $chairperson = auth()->user();
        
        if ($batch->chairperson_id !== $chairperson->id) {
            abort(403, 'You cannot retry this batch.');
        }

        // Reset error records to pending for re-validation
        $batch->gradeImportRecords()
            ->where('status', 'error')
            ->update(['status' => 'pending', 'error_message' => null]);

        $batch->update(['status' => 'pending']);

        return redirect()->route('chairperson.grade-batches.show', $batch)
            ->with('success', 'Failed records reset for retry.');
    }

    /**
     * Delete a batch
     */
    public function destroy(GradeImportBatch $batch)
    {
        $chairperson = auth()->user();
        
        if ($batch->chairperson_id !== $chairperson->id) {
            abort(403, 'You cannot delete this batch.');
        }

        if ($batch->status === 'approved') {
            return back()->with('error', 'Cannot delete approved batches.');
        }

        $batch->gradeImportRecords()->delete();
        $batch->delete();

        return redirect()->route('chairperson.grade-batches.index')
            ->with('success', 'Batch deleted successfully.');
    }
}
