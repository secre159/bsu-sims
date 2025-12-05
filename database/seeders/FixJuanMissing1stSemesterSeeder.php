<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use App\Services\GwaCalculationService;
use Illuminate\Database\Seeder;

class FixJuanMissing1stSemesterSeeder extends Seeder
{
    private $gwaService;

    public function __construct(GwaCalculationService $gwaService)
    {
        $this->gwaService = $gwaService;
    }

    /**
     * Add missing 1st semester 1st year enrollments for Juan Dela Cruz
     */
    public function run(): void
    {
        $this->command->info('Adding missing 1st semester enrollments for Juan Dela Cruz...');
        $this->command->info('');

        $juan = Student::where('student_id', 'TEST-2025-001')->first();
        
        if (!$juan) {
            $this->command->error('TEST-2025-001 not found');
            return;
        }

        // Get 2023-2024 1st semester academic year
        $academicYear = AcademicYear::where('year_code', '2023-2024-1')
            ->where('semester', '1st Semester')
            ->first();

        if (!$academicYear) {
            $this->command->error('2023-2024-1 academic year not found');
            return;
        }

        // Get 1st year 1st semester subjects for Juan's program
        $subjects = Subject::where('program_id', $juan->program_id)
            ->where('year_level', '1st Year')
            ->where('semester', '1st Semester')
            ->where('is_active', true)
            ->get();

        if ($subjects->isEmpty()) {
            $this->command->warn('No 1st year 1st semester subjects found for program');
            return;
        }

        $created = 0;
        foreach ($subjects as $subject) {
            // Check if enrollment already exists
            $exists = Enrollment::where('student_id', $juan->id)
                ->where('subject_id', $subject->id)
                ->where('academic_year_id', $academicYear->id)
                ->exists();

            if (!$exists) {
                Enrollment::create([
                    'student_id' => $juan->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $academicYear->id,
                    'status' => 'Completed',
                    'enrollment_type' => 'regular',
                    'grade' => $this->getRealisticGrade(),
                ]);
                $created++;
                $this->command->info("  ✓ Added: {$subject->code} - {$subject->name}");
            }
        }

        if ($created > 0) {
            // Recalculate GWA
            $this->gwaService->updateStudentStanding($juan);
            $juan->refresh();

            $this->command->info('');
            $this->command->info("✅ Added {$created} enrollments for 2023-2024-1 (1st Year, 1st Semester)");
            $this->command->info("   Updated GWA: {$juan->gwa}");
        } else {
            $this->command->info('No new enrollments needed');
        }
    }

    /**
     * Get a realistic grade using Philippine grading scale
     */
    private function getRealisticGrade(): string
    {
        $rand = rand(1, 100);
        
        // 70% get good grades (1.00-2.50)
        if ($rand <= 70) {
            $goodGrades = ['1.00', '1.25', '1.50', '1.75', '2.00', '2.25', '2.50'];
            return $goodGrades[array_rand($goodGrades)];
        }
        
        // 20% get passing but lower grades (2.75-3.00)
        if ($rand <= 90) {
            return ['2.75', '3.00'][rand(0, 1)];
        }
        
        // 8% get conditional pass (3.25-4.00)
        if ($rand <= 98) {
            return ['3.25', '3.50', '3.75', '4.00'][rand(0, 3)];
        }
        
        // 2% fail (5.00)
        return '5.00';
    }
}
