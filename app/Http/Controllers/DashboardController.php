<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Program;
use App\Models\Activity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'Active')->count();
        $totalPrograms = Program::count();
        $graduatedStudents = Student::where('status', 'Graduated')->count();

        // Program distribution data
        $programData = Program::withCount('students')
            ->get()
            ->map(function ($program) {
                return [
                    'name' => $program->code,
                    'count' => $program->students_count
                ];
            });

        // Year level distribution
        $yearLevelData = Student::select('year_level')
            ->selectRaw('count(*) as count')
            ->groupBy('year_level')
            ->orderBy('year_level')
            ->get();

        // Status distribution
        $statusData = Student::select('status')
            ->selectRaw('count(*) as count')
            ->groupBy('status')
            ->get();

        // Recent activities
        $recentActivities = Activity::latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalStudents',
            'activeStudents',
            'totalPrograms',
            'graduatedStudents',
            'programData',
            'yearLevelData',
            'statusData',
            'recentActivities'
        ));
    }
}
