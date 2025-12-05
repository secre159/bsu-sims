<?php

namespace App\Http\Controllers\Chairperson;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\GradeImportBatch;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ChairpersonDashboardController extends Controller
{
    public function index()
    {
        $chairperson = Auth::user();
        $departmentId = $chairperson->department_id;

        // Get subjects in chairperson's department
        $subjects = Subject::where('department_id', $departmentId)
            ->where('is_active', true)
            ->get();
        
        $subjectIds = $subjects->pluck('id');

        // Total students enrolled in chairperson's department subjects
        $totalStudents = Student::whereHas('enrollments', function ($query) use ($subjectIds) {
            $query->whereIn('subject_id', $subjectIds);
        })->distinct()->count();

        // Enrollments pending grades (for current academic year)
        $pendingGrades = Enrollment::whereIn('subject_id', $subjectIds)
            ->whereNull('grade')
            ->whereIn('status', ['Enrolled', 'Completed'])
            ->count();

        // Enrollments with completed grades
        $completedGrades = Enrollment::whereIn('subject_id', $subjectIds)
            ->whereNotNull('grade')
            ->count();

        // Grade import batches
        $totalBatches = GradeImportBatch::where('chairperson_id', $chairperson->id)->count();
        $pendingBatches = GradeImportBatch::where('chairperson_id', $chairperson->id)
            ->whereIn('status', ['pending', 'ready', 'submitted'])
            ->count();
        $approvedBatches = GradeImportBatch::where('chairperson_id', $chairperson->id)
            ->where('status', 'approved')
            ->count();
        $rejectedBatches = GradeImportBatch::where('chairperson_id', $chairperson->id)
            ->where('status', 'rejected')
            ->count();

        // Grade completion percentage
        $totalEnrollments = $pendingGrades + $completedGrades;
        $gradeCompletionPercentage = $totalEnrollments > 0 
            ? round(($completedGrades / $totalEnrollments) * 100, 1) 
            : 0;

        // Subjects with grade statistics
        $subjectsWithStats = Subject::where('department_id', $departmentId)
            ->where('is_active', true)
            ->withCount([
                'enrollments as total_enrollments',
                'enrollments as pending_grades' => function ($query) {
                    $query->whereNull('grade')->whereIn('status', ['Enrolled', 'Completed']);
                },
                'enrollments as completed_grades' => function ($query) {
                    $query->whereNotNull('grade');
                }
            ])
            ->having('total_enrollments', '>', 0)
            ->orderBy('pending_grades', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($subject) {
                $subject->completion_percentage = $subject->total_enrollments > 0
                    ? round(($subject->completed_grades / $subject->total_enrollments) * 100, 1)
                    : 0;
                return $subject;
            });

        // Recent grade-related activities (department scoped)
        $recentActivities = Activity::where('user_id', $chairperson->id)
            ->where(function ($query) {
                $query->where('subject_type', 'LIKE', '%Grade%')
                      ->orWhere('subject_type', 'LIKE', '%Enrollment%')
                      ->orWhere('action', 'LIKE', '%grade%');
            })
            ->latest()
            ->limit(5)
            ->get();

        // Batch status data for chart (only include non-zero counts)
        $batchStatusData = collect([
            ['status' => 'Pending', 'count' => $pendingBatches],
            ['status' => 'Approved', 'count' => $approvedBatches],
            ['status' => 'Rejected', 'count' => $rejectedBatches],
        ])->filter(function ($item) {
            return $item['count'] > 0;
        })->values()->toArray();

        return view('chairperson.dashboard', compact(
            'totalStudents',
            'pendingGrades',
            'completedGrades',
            'totalBatches',
            'pendingBatches',
            'approvedBatches',
            'rejectedBatches',
            'gradeCompletionPercentage',
            'subjectsWithStats',
            'recentActivities',
            'batchStatusData',
            'subjects'
        ));
    }
}
