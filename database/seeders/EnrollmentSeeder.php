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
        // Get both 1st and 2nd semester academic years
        $firstSemester = AcademicYear::where('semester', '1st Semester')
            ->where('is_current', true)
            ->first();
        
        if (!$firstSemester) {
            $firstSemester = AcademicYear::where('semester', '1st Semester')->first();
        }
        
        $secondSemester = AcademicYear::where('semester', '2nd Semester')
            ->where('year_code', 'LIKE', $firstSemester ? substr($firstSemester->year_code, 0, 9) . '%' : '%')
            ->first();
        
        if (!$firstSemester) {
            echo "\n[EnrollmentSeeder] Warning: No 1st semester academic year found. Skipping enrollment seeding.\n";
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
                // Determine correct academic year based on subject's semester
                $academicYearForSubject = $firstSemester; // Default to 1st semester
                
                if ($subject->semester === '2nd Semester' && $secondSemester) {
                    $academicYearForSubject = $secondSemester;
                }
                
                // Check if already enrolled
                $existingEnrollment = Enrollment::where('student_id', $student->id)
                    ->where('subject_id', $subject->id)
                    ->where('academic_year_id', $academicYearForSubject->id)
                    ->exists();

                if ($existingEnrollment) {
                    continue;
                }

                // Enroll in appropriate semester based on subject
                // Status: "Enrolled" - no grades yet (grades entered later)
                Enrollment::create([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $academicYearForSubject->id,
                    'status' => 'Enrolled',
                    'grade' => null,
                    'remarks' => null,
                ]);
            }
        }
    }
}
