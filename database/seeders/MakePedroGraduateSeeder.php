<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use App\Services\GwaCalculationService;
use Illuminate\Database\Seeder;

class MakePedroGraduateSeeder extends Seeder
{
    private $gwaService;

    public function __construct(GwaCalculationService $gwaService)
    {
        $this->gwaService = $gwaService;
    }

    /**
     * Convert Pedro Santos (TEST-2025-003) to a graduated student
     */
    public function run(): void
    {
        $this->command->info('Converting Pedro Santos to Graduate status...');
        $this->command->info('');

        $student = Student::where('student_id', 'TEST-2025-003')->first();
        
        if (!$student) {
            $this->command->error('TEST-2025-003 not found');
            return;
        }

        // Update student to graduated status
        $student->update([
            'year_level' => '4th Year',
            'status' => 'Graduated',
            'student_type' => 'Candidate for graduation',
        ]);

        $this->command->info("✓ Updated student status to Graduated");

        // Get all current enrollments and mark as completed with grades
        $enrollments = Enrollment::where('student_id', $student->id)
            ->where('status', 'Enrolled')
            ->get();

        foreach ($enrollments as $enrollment) {
            $enrollment->update([
                'status' => 'Completed',
                'grade' => $this->getRealisticGrade(),
            ]);
        }

        $this->command->info("✓ Marked all enrollments as completed ({$enrollments->count()} enrollments)");

        // Add 4th year enrollments if missing
        $this->add4thYearEnrollments($student);

        // Recalculate GWA
        $this->gwaService->updateStudentStanding($student);
        $student->refresh();

        $this->command->info("✓ Recalculated GWA: {$student->gwa}");
        $this->command->info('');
        $this->command->info("✅ Pedro Santos (TEST-2025-003) is now a Graduate!");
        $this->command->info("   Status: {$student->status}");
        $this->command->info("   Year Level: {$student->year_level}");
        $this->command->info("   Student Type: {$student->student_type}");
        $this->command->info("   Final GWA: {$student->gwa}");
    }

    /**
     * Add 4th year enrollments if they don't exist
     */
    private function add4thYearEnrollments(Student $student): void
    {
        $program = $student->program;
        
        // Get the most recent completed academic year (2024-2025, 2nd Semester)
        $lastYear = AcademicYear::where('year_code', 'like', '2024-2025%')
            ->where('semester', '2nd Semester')
            ->first();

        if (!$lastYear) {
            $this->command->warn('Could not find 2024-2025 2nd Semester academic year');
            return;
        }

        // Check if student already has 4th year enrollments
        $existing4thYear = Enrollment::where('student_id', $student->id)
            ->whereHas('subject', function($q) {
                $q->where('year_level', '4th Year');
            })
            ->count();

        if ($existing4thYear > 0) {
            $this->command->info("  → Already has {$existing4thYear} 4th year enrollments");
            return;
        }

        // Get 4th year subjects for both semesters
        $subjects = Subject::where('program_id', $program->id)
            ->where('year_level', '4th Year')
            ->where('is_active', true)
            ->get();

        if ($subjects->isEmpty()) {
            $this->command->warn('  → No 4th year subjects found for program');
            return;
        }

        $academicYears = AcademicYear::where('year_code', 'like', '2024-2025%')
            ->orderBy('semester')
            ->get();

        $created = 0;
        foreach ($academicYears as $academicYear) {
            $semesterSubjects = $subjects->where('semester', $academicYear->semester);
            
            foreach ($semesterSubjects as $subject) {
                // Check if enrollment already exists
                $exists = Enrollment::where('student_id', $student->id)
                    ->where('subject_id', $subject->id)
                    ->where('academic_year_id', $academicYear->id)
                    ->exists();

                if (!$exists) {
                    Enrollment::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'academic_year_id' => $academicYear->id,
                        'status' => 'Completed',
                        'enrollment_type' => 'regular',
                        'grade' => $this->getRealisticGrade(),
                    ]);
                    $created++;
                }
            }
        }

        if ($created > 0) {
            $this->command->info("  → Added {$created} 4th year enrollments");
        }
    }

    /**
     * Get a realistic grade using Philippine grading scale
     * Weighted towards good grades for a graduate
     */
    private function getRealisticGrade(): string
    {
        $rand = rand(1, 100);
        
        // 80% get good grades (1.00-2.50) - higher for graduates
        if ($rand <= 80) {
            $goodGrades = ['1.00', '1.25', '1.50', '1.75', '2.00', '2.25', '2.50'];
            return $goodGrades[array_rand($goodGrades)];
        }
        
        // 18% get passing but lower grades (2.75-3.00)
        if ($rand <= 98) {
            return ['2.75', '3.00'][rand(0, 1)];
        }
        
        // 2% get conditional pass (3.25)
        return '3.25';
    }
}
