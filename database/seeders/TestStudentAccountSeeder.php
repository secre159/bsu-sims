<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Program;
use App\Models\AcademicYear;
use App\Models\StudentUser;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Services\GwaCalculationService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestStudentAccountSeeder extends Seeder
{
    private $gwaService;

    public function __construct(GwaCalculationService $gwaService)
    {
        $this->gwaService = $gwaService;
    }

    /**
     * Create 5 test students with known credentials for testing
     */
    public function run(): void
    {
        $this->command->info('Creating 5 test student accounts...');

        $program = Program::first();
        $academicYear = AcademicYear::where('is_current', true)->first();

        if (!$program || !$academicYear) {
            $this->command->error('Please run ProgramSeeder and AcademicYearSeeder first.');
            return;
        }

        $testStudents = [
            [
                'student_id' => 'TEST-2025-001',
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'middle_name' => 'Santos',
                'birthdate' => '2005-01-15',
                'gender' => 'Male',
                'email_address' => 'juan.delacruz@student.bsu-bokod.edu.ph',
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
                'has_enrollments' => true,
            ],
            [
                'student_id' => 'TEST-2025-002',
                'first_name' => 'Maria',
                'last_name' => 'Reyes',
                'middle_name' => 'Garcia',
                'birthdate' => '2005-03-22',
                'gender' => 'Female',
                'email_address' => 'maria.reyes@student.bsu-bokod.edu.ph',
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
                'has_enrollments' => true,
            ],
            [
                'student_id' => 'TEST-2025-003',
                'first_name' => 'Pedro',
                'last_name' => 'Santos',
                'middle_name' => 'Cruz',
                'birthdate' => '2004-07-10',
                'gender' => 'Male',
                'email_address' => 'pedro.santos@student.bsu-bokod.edu.ph',
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
                'has_enrollments' => true,
            ],
            [
                'student_id' => 'TEST-2025-004',
                'first_name' => 'Ana',
                'last_name' => 'Mendoza',
                'middle_name' => 'Torres',
                'birthdate' => '2005-09-05',
                'gender' => 'Female',
                'email_address' => 'ana.mendoza@student.bsu-bokod.edu.ph',
            ],
            [
                'student_id' => 'TEST-2025-005',
                'first_name' => 'Jose',
                'last_name' => 'Bautista',
                'middle_name' => 'Ramos',
                'birthdate' => '2005-11-30',
                'gender' => 'Male',
                'email_address' => 'jose.bautista@student.bsu-bokod.edu.ph',
            ],
        ];

        foreach ($testStudents as $data) {
            // Skip if already exists
            if (Student::where('student_id', $data['student_id'])->exists()) {
                $this->command->warn("  • Skipped {$data['student_id']} - already exists");
                continue;
            }

            $student = Student::create([
                'student_id' => $data['student_id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'],
                'birthdate' => $data['birthdate'],
                'gender' => $data['gender'],
                'email_address' => $data['email_address'],
                'contact_number' => $data['contact_number'] ?? '09' . rand(100000000, 999999999),
                'address' => $data['home_address'] ?? 'Brgy. Poblacion, Bokod, Benguet',
                'home_address' => $data['home_address'] ?? null,
                'address_while_studying' => $data['address_while_studying'] ?? null,
                'mother_name' => $data['mother_name'] ?? null,
                'mother_contact_number' => $data['mother_contact_number'] ?? null,
                'father_name' => $data['father_name'] ?? null,
                'father_contact_number' => $data['father_contact_number'] ?? null,
                'emergency_contact_person' => $data['emergency_contact_person'] ?? null,
                'emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? null,
                'emergency_contact_number' => $data['emergency_contact_number'] ?? null,
                'program_id' => $program->id,
                'year_level' => $data['year_level'] ?? '1st Year',
                'status' => 'Active',
                'attendance_type' => 'Regular',
                'enrollment_date' => now()->subYears($this->getYearLevelNumber($data['year_level'] ?? '1st Year') - 1),
                'academic_year_id' => $academicYear->id,
            ]);

            // Add enrollment history if specified
            if ($data['has_enrollments'] ?? false) {
                $this->createEnrollmentHistory($student);
            }

            // Create portal account
            $last4 = substr($student->student_id, -4);
            $birthYear = date('Y', strtotime($student->birthdate));
            $defaultPassword = "BSU{$last4}{$birthYear}";

            // Handle duplicate emails
            $email = $data['email_address'];
            $counter = 1;
            while (StudentUser::where('email', $email)->exists()) {
                $emailParts = explode('@', $data['email_address']);
                $email = $emailParts[0] . $counter . '@' . $emailParts[1];
                $counter++;
            }

            StudentUser::create([
                'student_id' => $student->id,
                'email' => $email,
                'password' => Hash::make($defaultPassword),
            ]);

            $this->command->info("  ✓ {$data['student_id']} - {$student->full_name}");
            $this->command->info("    Email: {$data['email_address']}");
            $this->command->info("    Password: {$defaultPassword}");
        }

        $this->command->info('');
        $this->command->info('✅ Test accounts created! Use these credentials to log in to the student portal.');
    }

    /**
     * Helper to convert year level string to number
     */
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

    /**
     * Create realistic enrollment history for a student
     */
    private function createEnrollmentHistory(Student $student): void
    {
        $currentYearLevel = $this->getYearLevelNumber($student->year_level);
        $program = $student->program;
        
        // Get academic years (past and current)
        $academicYears = AcademicYear::orderBy('year_code')->get();
        if ($academicYears->count() < 2) {
            $this->command->warn("Not enough academic years to create enrollment history");
            return;
        }

        $currentYear = $academicYears->firstWhere('is_current', true) ?? $academicYears->last();
        $previousYears = $academicYears->where('year_code', '<', $currentYear->year_code)->take($currentYearLevel);

        // Create enrollments for completed years
        $yearLevelNum = 1;
        foreach ($previousYears as $academicYear) {
            $subjects = Subject::where('program_id', $program->id)
                ->where('year_level', $this->getYearLevelString($yearLevelNum))
                ->where('semester', $academicYear->semester)
                ->where('is_active', true)
                ->get();

            foreach ($subjects as $subject) {
                // Past enrollments are completed with grades
                Enrollment::create([
                    'student_id' => $student->student_id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $academicYear->id,
                    'status' => 'Completed',
                    'enrollment_type' => 'regular',
                    'grade' => $this->getRealisticGrade(),
                ]);
            }

            // Move to next year level at end of semester
            if ($academicYear->semester == '2nd Semester') {
                $yearLevelNum++;
            }
        }

        // Create enrollments for current year (no grades yet)
        if ($currentYearLevel <= 4) { // Only if not yet graduated
            $currentSemester = $currentYear->semester;
            $subjects = Subject::where('program_id', $program->id)
                ->where('year_level', $student->year_level)
                ->where('semester', $currentSemester)
                ->where('is_active', true)
                ->get();

            foreach ($subjects as $subject) {
                Enrollment::create([
                    'student_id' => $student->student_id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $currentYear->id,
                    'status' => 'Enrolled',
                    'enrollment_type' => 'regular',
                    'grade' => null,
                ]);
            }
        }

        // Calculate GWA based on all completed enrollments
        $this->gwaService->calculateAndUpdateGwa($student);
        
        $this->command->info("    → Enrollment history created");
    }

    /**
     * Helper to convert year level number to string
     */
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

    /**
     * Get a realistic grade using Philippine grading scale
     * Weighted towards passing grades (1.00-3.00)
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
