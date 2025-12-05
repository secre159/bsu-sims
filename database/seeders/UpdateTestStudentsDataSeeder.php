<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use App\Services\GwaCalculationService;
use Illuminate\Database\Seeder;

class UpdateTestStudentsDataSeeder extends Seeder
{
    private $gwaService;

    public function __construct(GwaCalculationService $gwaService)
    {
        $this->gwaService = $gwaService;
    }

    /**
     * Update the first 3 test students with complete personal info and enrollment history
     */
    public function run(): void
    {
        $this->command->info('Updating first 3 test students with detailed data...');
        $this->command->info('');

        // Update TEST-2025-001: Juan Dela Cruz - 2nd Year BSIT
        $this->updateStudent001();

        // Update TEST-2025-002: Maria Reyes - 1st Year BPA
        $this->updateStudent002();

        // Update TEST-2025-003: Pedro Santos - 3rd Year BTLEd
        $this->updateStudent003();

        $this->command->info('');
        $this->command->info('✅ Test student data updated successfully!');
    }

    private function updateStudent001(): void
    {
        $student = Student::where('student_id', 'TEST-2025-001')->first();
        if (!$student) {
            $this->command->warn('TEST-2025-001 not found');
            return;
        }

        // Update personal information
        $student->update([
            'contact_number' => '09171234567',
            'home_address' => 'Sitio Proper, Brgy. Tikey, Bokod, Benguet',
            'address_while_studying' => 'Boarding House A, Poblacion, Bokod, Benguet',
            'mother_name' => 'Rosa Santos Dela Cruz',
            'mother_contact_number' => '09281234567',
            'father_name' => 'Roberto Dela Cruz',
            'father_contact_number' => '09191234567',
            'emergency_contact_person' => 'Rosa Santos Dela Cruz',
            'emergency_contact_relationship' => 'Mother',
            'emergency_contact_number' => '09281234567',
            'year_level' => '2nd Year',
            'enrollment_date' => now()->subYears(1),
        ]);

        // Create enrollment history
        $this->createEnrollmentHistory($student);
        
        $this->command->info("  ✓ TEST-2025-001 (Juan Dela Cruz) - 2nd Year BSIT");
    }

    private function updateStudent002(): void
    {
        $student = Student::where('student_id', 'TEST-2025-002')->first();
        if (!$student) {
            $this->command->warn('TEST-2025-002 not found');
            return;
        }

        // Update personal information
        $student->update([
            'contact_number' => '09187654321',
            'home_address' => 'Brgy. Karao, Bokod, Benguet',
            'address_while_studying' => 'Family Home, Brgy. Karao, Bokod, Benguet',
            'mother_name' => 'Carmen Garcia Reyes',
            'mother_contact_number' => '09267654321',
            'father_name' => 'Jose Reyes',
            'father_contact_number' => '09177654321',
            'emergency_contact_person' => 'Carmen Garcia Reyes',
            'emergency_contact_relationship' => 'Mother',
            'emergency_contact_number' => '09267654321',
            'year_level' => '1st Year',
        ]);

        // Create enrollment history
        $this->createEnrollmentHistory($student);
        
        $this->command->info("  ✓ TEST-2025-002 (Maria Reyes) - 1st Year BPA");
    }

    private function updateStudent003(): void
    {
        $student = Student::where('student_id', 'TEST-2025-003')->first();
        if (!$student) {
            $this->command->warn('TEST-2025-003 not found');
            return;
        }

        // Update personal information
        $student->update([
            'contact_number' => '09159876543',
            'home_address' => 'Sitio Dalonot, Brgy. Ekip, Bokod, Benguet',
            'address_while_studying' => 'Apartment 12, Poblacion, Bokod, Benguet',
            'mother_name' => 'Luz Cruz Santos',
            'mother_contact_number' => '09259876543',
            'father_name' => 'Manuel Santos',
            'father_contact_number' => '09169876543',
            'emergency_contact_person' => 'Luz Cruz Santos',
            'emergency_contact_relationship' => 'Mother',
            'emergency_contact_number' => '09259876543',
            'year_level' => '3rd Year',
            'enrollment_date' => now()->subYears(2),
        ]);

        // Create enrollment history
        $this->createEnrollmentHistory($student);
        
        $this->command->info("  ✓ TEST-2025-003 (Pedro Santos) - 3rd Year BTLEd");
    }

    private function createEnrollmentHistory(Student $student): void
    {
        // Delete existing enrollments to start fresh
        Enrollment::where('student_id', $student->id)->delete();

        $currentYearLevel = $this->getYearLevelNumber($student->year_level);
        $program = $student->program;
        
        // Get academic years
        $academicYears = AcademicYear::orderBy('year_code')->get();
        $currentYear = $academicYears->firstWhere('is_current', true);
        
        // Get past years based on student's current year level
        $yearsNeeded = ($currentYearLevel * 2) - 1; // Semesters needed (e.g., 2nd year = 3 semesters)
        $pastYears = $academicYears->where('year_code', '<', $currentYear->year_code)
            ->sortByDesc('year_code')
            ->take($yearsNeeded);

        // Create enrollments for completed semesters
        $yearLevelNum = 1;
        $semesterCount = 0;
        
        foreach ($pastYears->reverse() as $academicYear) {
            $subjects = Subject::where('program_id', $program->id)
                ->where('year_level', $this->getYearLevelString($yearLevelNum))
                ->where('semester', $academicYear->semester)
                ->where('is_active', true)
                ->get();

            if ($subjects->count() > 0) {
                foreach ($subjects as $subject) {
                    Enrollment::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'academic_year_id' => $academicYear->id,
                        'status' => 'Completed',
                        'enrollment_type' => 'regular',
                        'grade' => $this->getRealisticGrade(),
                    ]);
                }
                $this->command->info("    → {$academicYear->year_code} - " . $subjects->count() . " subjects");
            }

            // Advance year level after 2nd semester
            $semesterCount++;
            if ($academicYear->semester == '2nd Semester') {
                $yearLevelNum++;
            }
        }

        // Create enrollments for current semester (no grades yet)
        if ($currentYearLevel <= 4) {
            $currentSemester = $currentYear->semester;
            $subjects = Subject::where('program_id', $program->id)
                ->where('year_level', $student->year_level)
                ->where('semester', $currentSemester)
                ->where('is_active', true)
                ->get();

            if ($subjects->count() > 0) {
                foreach ($subjects as $subject) {
                    Enrollment::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'academic_year_id' => $currentYear->id,
                        'status' => 'Enrolled',
                        'enrollment_type' => 'regular',
                        'grade' => null,
                    ]);
                }
                $this->command->info("    → {$currentYear->year_code} (Current) - " . $subjects->count() . " subjects");
            }
        }

        // Calculate GWA
        $this->gwaService->updateStudentStanding($student);
        $student->refresh();
        if ($student->gwa) {
            $this->command->info("    → GWA: {$student->gwa}");
        }
    }

    private function getYearLevelNumber(string $yearLevel): int
    {
        return match($yearLevel) {
            '1st Year' => 1,
            '2nd Year' => 2,
            '3rd Year' => 3,
            '4th Year' => 4,
            '5th Year' => 5,
            default => 1,
        };
    }

    private function getYearLevelString(int $yearLevelNum): string
    {
        return match($yearLevelNum) {
            1 => '1st Year',
            2 => '2nd Year',
            3 => '3rd Year',
            4 => '4th Year',
            5 => '5th Year',
            default => '1st Year',
        };
    }

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
