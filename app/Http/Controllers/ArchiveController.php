<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ArchivedStudent;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::all();
        $archives = ArchivedStudent::with('program')
            ->select('archived_school_year', 'archived_semester')
            ->selectRaw('COUNT(*) as total_students')
            ->groupBy('archived_school_year', 'archived_semester')
            ->orderBy('archived_at', 'desc')
            ->get();

        return view('archive.index', compact('academicYears', 'archives'));
    }

    public function show($schoolYear, $semester)
    {
        $archivedStudents = ArchivedStudent::with('program')
            ->where('archived_school_year', $schoolYear)
            ->where('archived_semester', $semester)
            ->orderBy('name')
            ->paginate(15);

        return view('archive.show', compact('archivedStudents', 'schoolYear', 'semester'));
    }

    public function create()
    {
        $academicYears = AcademicYear::all();
        
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'Active')->count(),
            'graduated' => Student::where('status', 'Graduated')->count(),
        ];

        return view('archive.create', compact('academicYears', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year' => 'required|string',
            'semester' => 'required|string',
            'archive_reason' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $students = Student::with('program')->get();

            foreach ($students as $student) {
                ArchivedStudent::create([
                    'student_data' => $student->toArray(),
                    'student_id' => $student->student_id,
                    'name' => $student->last_name . ', ' . $student->first_name,
                    'program_id' => $student->program_id,
                    'year_level' => $student->year_level,
                    'status' => $student->status,
                    'archived_school_year' => $validated['school_year'],
                    'archived_semester' => $validated['semester'],
                    'archived_at' => now(),
                    'archived_by' => auth()->id(),
                    'archive_reason' => $validated['archive_reason'],
                ]);
            }

            // Optionally delete students after archiving
            if ($request->has('delete_after_archive')) {
                Student::truncate();
            }

            DB::commit();

            return redirect()->route('archive.index')
                ->with('success', 'Successfully archived ' . $students->count() . ' students for ' . $validated['school_year'] . ' - ' . $validated['semester']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to archive students: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        $archived = ArchivedStudent::findOrFail($id);
        
        DB::beginTransaction();

        try {
            // Restore student from archived data
            $studentData = $archived->student_data;
            unset($studentData['id']); // Remove old ID to avoid conflicts
            
            Student::create($studentData);
            
            // Delete from archive
            $archived->delete();
            
            DB::commit();

            return back()->with('success', 'Student restored successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to restore student: ' . $e->getMessage());
        }
    }

    public function destroy($schoolYear, $semester)
    {
        ArchivedStudent::where('archived_school_year', $schoolYear)
            ->where('archived_semester', $semester)
            ->delete();

        return redirect()->route('archive.index')
            ->with('success', 'Archive deleted successfully!');
    }
}
