<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prefer the current academic year; fallback to any 2024-2025 year
        $currentAcademicYear = AcademicYear::where('is_current', true)->first();
        if (!$currentAcademicYear) {
            $currentAcademicYear = AcademicYear::where('year_code', 'LIKE', '2024-2025%')->first();
        }
        if (!$currentAcademicYear) {
            echo "\n[EnrollmentSeeder] Warning: No active academic year found. Skipping enrollment seeding.\n";
            return;
        }

        $students = Student::with('program')->get();

        foreach ($students as $student) {
            // Get subjects for this student's program and appropriate year level
            $studentYearLevel = $student->year_level;
            
            // Get subjects matching student's program and year level
            $availableSubjects = Subject::where('program_id', $student->program_id)
                ->where('year_level', $studentYearLevel)
                ->get();

            if ($availableSubjects->isEmpty()) {
                continue;
            }

            // Enroll in 4-5 random subjects from available subjects
            $enrollmentCount = rand(4, 5);
            $selectedSubjects = $availableSubjects->random(min($enrollmentCount, $availableSubjects->count()));

            foreach ($selectedSubjects as $subject) {
                // Check if already enrolled
                $existingEnrollment = Enrollment::where('student_id', $student->id)
                    ->where('subject_id', $subject->id)
                    ->where('academic_year_id', $currentAcademicYear->id)
                    ->exists();

                if ($existingEnrollment) {
                    continue;
                }

                // Current semester: Enrolled, NO GRADE YET
                // In real system, grades are only entered AFTER course completion
                // Status must be "Enrolled" or "Completed" with actual course performance
                Enrollment::create([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $currentAcademicYear->id,
                    'status' => 'Enrolled',      // Only "Enrolled" - no grades yet
                    'grade' => null,             // No grade until semester ends
                    'remarks' => null,           // No remarks yet
                ]);
            }
        }
    }
}
