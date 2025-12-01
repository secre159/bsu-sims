<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Services\SemesterTransitionService;
use Illuminate\Http\Request;

class SemesterTransitionController extends Controller
{
    private SemesterTransitionService $transitionService;

    public function __construct(SemesterTransitionService $transitionService)
    {
        $this->transitionService = $transitionService;
    }

    /**
     * Show semester transition wizard
     */
    public function index()
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        
        return view('admin.semester-transition.index', compact('academicYears'));
    }

    /**
     * Validate year transition prerequisites
     */
    public function validate(Request $request)
    {
        $validated = $request->validate([
            'current_year_id' => 'required|exists:academic_years,id',
            'next_year_id' => 'required|exists:academic_years,id',
        ]);

        $currentYear = AcademicYear::find($validated['current_year_id']);
        $nextYear = AcademicYear::find($validated['next_year_id']);

        if ($currentYear->id === $nextYear->id) {
            return back()->withErrors(['next_year_id' => 'Next year must be different from current year']);
        }

        // Check if next year already has enrollments
        $existingEnrollments = $nextYear->enrollments()->count();
        if ($existingEnrollments > 0) {
            return back()->withErrors(['next_year_id' => 'Next year already has enrollments. Cannot transition.']);
        }

        // Prepare transition validation
        $validation = $this->transitionService->prepareYearTransition($currentYear);

        // Store in session for confirmation
        session([
            'transition_data' => [
                'current_year_id' => $currentYear->id,
                'next_year_id' => $nextYear->id,
                'current_year_name' => $currentYear->year,
                'next_year_name' => $nextYear->year,
            ]
        ]);

        return view('admin.semester-transition.validate', [
            'currentYear' => $currentYear,
            'nextYear' => $nextYear,
            'validation' => $validation,
        ]);
    }

    /**
     * Show final confirmation before execution
     */
    public function confirm(Request $request)
    {
        $transitionData = session('transition_data');
        
        if (!$transitionData) {
            return redirect()->route('semester-transition.index')
                ->withErrors('Session expired. Please start over.');
        }

        $currentYear = AcademicYear::find($transitionData['current_year_id']);
        $nextYear = AcademicYear::find($transitionData['next_year_id']);

        // Re-validate to show fresh statistics
        $validation = $this->transitionService->prepareYearTransition($currentYear);

        return view('admin.semester-transition.confirm', [
            'currentYear' => $currentYear,
            'nextYear' => $nextYear,
            'validation' => $validation,
        ]);
    }

    /**
     * Execute the semester transition
     */
    public function execute(Request $request)
    {
        $validated = $request->validate([
            'current_year_id' => 'required|exists:academic_years,id',
            'next_year_id' => 'required|exists:academic_years,id',
        ]);

        $currentYear = AcademicYear::find($validated['current_year_id']);
        $nextYear = AcademicYear::find($validated['next_year_id']);

        // Validate years are different
        if ($currentYear->id === $nextYear->id) {
            return back()->withErrors('Current and next year must be different');
        }

        // Check if next year already has enrollments
        if ($nextYear->enrollments()->count() > 0) {
            return back()->withErrors('Next year already has enrollments. Cannot transition.');
        }

        try {
            // Execute transition
            $results = $this->transitionService->executeYearTransition($currentYear, $nextYear);
            
            // Set next year as current and unset current year
            AcademicYear::query()->update(['is_current' => false]);
            $nextYear->update(['is_current' => true]);

            return back()->with('success', "Transition completed! Processed {$results['students_processed']} students. {$nextYear->year_code} ({$nextYear->semester}) is now current.");
        } catch (\Exception $e) {
            return back()->withErrors('Error executing transition: ' . $e->getMessage());
        }
    }
}
