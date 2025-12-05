<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Program;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use App\Models\StudentUser;
use App\Services\GwaCalculationService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RealisticTestDataSeeder extends Seeder
{
    private $gwaService;
    private $academicYears;
    private $currentYear;
    private $programs;
    
    // Filipino first names
    private $firstNames = [
        'Juan', 'Maria', 'Jose', 'Ana', 'Pedro', 'Rosa', 'Miguel', 'Sofia',
        'Carlos', 'Isabel', 'Ramon', 'Carmen', 'Luis', 'Elena', 'Angel',
        'Cristina', 'Roberto', 'Patricia', 'Fernando', 'Teresa', 'Ricardo',
        'Lucia', 'Antonio', 'Monica', 'Francisco', 'Beatriz', 'Diego',
        'Gabriela', 'Manuel', 'Victoria', 'Enrique', 'Diana', 'Rafael',
        'Andrea', 'Jorge', 'Melissa', 'Alberto', 'Natalia', 'Alejandro',
        'Camila', 'Eduardo', 'Valeria', 'Sergio', 'Daniela', 'Pablo',
        'Adriana', 'Javier', 'Laura', 'Andres', 'Isabella'
    ];
    
    // Filipino last names
    private $lastNames = [
        'Reyes', 'Santos', 'Cruz', 'Bautista', 'Garcia', 'Gonzales', 'Ramos',
        'Flores', 'Mendoza', 'Torres', 'Rivera', 'Castro', 'Pascual', 'Aquino',
        'Villanueva', 'Fernandez', 'Ramirez', 'Santiago', 'Marquez', 'Lopez',
        'Morales', 'Castillo', 'Hernandez', 'Diaz', 'Soriano', 'Mercado',
        'Dela Cruz', 'San Jose', 'Navarro', 'Valencia', 'Zamora', 'Espinosa',
        'Aguilar', 'Salazar', 'Rojas', 'Domingo', 'Valdez', 'Gutierrez',
        'Romero', 'Jimenez', 'Vargas', 'Medina', 'Ortiz', 'Pena', 'Silva'
    ];

    public function __construct(GwaCalculationService $gwaService)
    {
        $this->gwaService = $gwaService;
    }

    public function run(): void
    {
        $this->command->info('🌱 Starting realistic test data generation...');
        
        // Get data
        $this->academicYears = AcademicYear::orderBy('year_code')->get();
        $this->currentYear = AcademicYear::where('is_current', true)->first();
        $this->programs = Program::all();

        if ($this->programs->isEmpty()) {
            $this->command->error('No programs found! Please run ProgramSeeder first.');
            return;
        }

        if ($this->academicYears->isEmpty()) {
            $this->command->error('No academic years found! Please run AcademicYearSeeder first.');
            return;
        }

        // Generate students for each program and year level
        foreach ($this->programs as $program) {
            $this->command->info("Generating students for {$program->name}...");
            $this->generateStudentsForProgram($program);
        }

        $this->command->info('✅ Realistic test data seeding completed!');
    }

    private function generateStudentsForProgram($program)
    {
        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        
        foreach ($yearLevels as $yearLevel) {
            // Generate different numbers of students per year level
            $count = match($yearLevel) {
                '1st Year' => rand(15, 25),
                '2nd Year' => rand(12, 20),
                '3rd Year' => rand(10, 15),
                '4th Year' => rand(8, 12),
            };

            for ($i = 0; $i < $count; $i++) {
                $this->createStudent($program, $yearLevel);
            }
        }
    }

    private function createStudent($program, $yearLevel)
    {
        $firstName = $this->firstNames[array_rand($this->firstNames)];
        $lastName = $this->lastNames[array_rand($this->lastNames)];
        $middleName = $this->lastNames[array_rand($this->lastNames)];
        
        // Generate student ID: YEAR-PROG-###
        $year = now()->year;
        $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $studentId = "{$year}-{$program->code}-{$random}";
        
        // Check if ID exists, regenerate if needed
        while (Student::where('student_id', $studentId)->exists()) {
            $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $studentId = "{$year}-{$program->code}-{$random}";
        }

        $birthYear = rand(2000, 2006);
        $birthMonth = rand(1, 12);
        $birthDay = rand(1, 28);

        // Determine status based on year level (add variety)
        $statusDist = $this->getStatusDistribution($yearLevel);
        $status = $this->weightedRandom($statusDist);

        // Attendance type
        $isIrregular = rand(1, 100) <= 20; // 20% irregular
        $attendanceType = $isIrregular ? 'Irregular' : 'Regular';

        $student = Student::create([
            'student_id' => $studentId,
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'suffix' => rand(1, 100) <= 5 ? 'Jr.' : null,
            'birthdate' => "{$birthYear}-{$birthMonth}-{$birthDay}",
            'gender' => rand(0, 1) ? 'Male' : 'Female',
            'contact_number' => '09' . rand(100000000, 999999999),
            'email_address' => strtolower($firstName . '.' . $lastName . '@student.bsu-bokod.edu.ph'),
            'address' => $this->generateAddress(),
            'program_id' => $program->id,
            'year_level' => $yearLevel,
            'status' => $status,
            'attendance_type' => $attendanceType,
            'is_irregular' => $isIrregular,
            'enrollment_date' => $this->getEnrollmentDate($yearLevel),
            'academic_year_id' => $this->currentYear->id,
        ]);

        // Create student portal account
        $this->createStudentUser($student);

        // Generate enrollments and grades
        $this->generateEnrollments($student, $yearLevel, $status);

        $this->command->info("  ✓ Created: {$student->full_name} ({$studentId}) - {$yearLevel} {$status}");
    }

    private function createStudentUser($student)
    {
        // Skip if already exists
        if (StudentUser::where('student_id', $student->id)->exists()) {
            return;
        }

        // Generate unique email
        $baseEmail = $student->email_address ?? $student->student_id . '@student.bsu-bokod.edu.ph';
        $email = $baseEmail;
        $counter = 1;
        
        // Check for duplicates and append counter if needed
        while (StudentUser::where('email', $email)->exists()) {
            $emailParts = explode('@', $baseEmail);
            $email = $emailParts[0] . $counter . '@' . $emailParts[1];
            $counter++;
        }

        // Default password: BSU + last 4 of student ID + birth year
        $last4 = substr($student->student_id, -4);
        $birthYear = date('Y', strtotime($student->birthdate));
        $defaultPassword = "BSU{$last4}{$birthYear}";

        StudentUser::create([
            'student_id' => $student->id,
            'email' => $email,
            'password' => Hash::make($defaultPassword),
        ]);
    }

    private function generateEnrollments($student, $yearLevel, $status)
    {
        $yearNum = (int)substr($yearLevel, 0, 1);
        
        // Get subjects for this student's program and year levels up to current
        for ($level = 1; $level <= $yearNum; $level++) {
            $levelName = "{$level}st Year";
            if ($level == 2) $levelName = '2nd Year';
            if ($level == 3) $levelName = '3rd Year';
            if ($level == 4) $levelName = '4th Year';

            $subjects = Subject::where('program_id', $student->program_id)
                ->where('year_level', $levelName)
                ->where('is_active', true)
                ->get();

            foreach ($subjects as $subject) {
                $this->createEnrollment($student, $subject, $level, $yearNum, $status);
            }
        }

        // Calculate GWA after all enrollments
        $this->gwaService->updateStudentStanding($student);
    }

    private function createEnrollment($student, $subject, $completedLevel, $currentLevel, $studentStatus)
    {
        // Determine academic year (past years for completed levels)
        $yearsAgo = $currentLevel - $completedLevel;
        $academicYearIndex = max(0, $this->academicYears->count() - 1 - $yearsAgo);
        $academicYear = $this->academicYears[$academicYearIndex] ?? $this->currentYear;

        // Determine enrollment status and grade
        if ($completedLevel < $currentLevel) {
            // Past subjects - completed or failed
            $enrollmentStatus = 'Completed';
            $grade = $this->generateGrade($student->is_irregular, $studentStatus, true);
            
            if (in_array($grade, ['5.00', 'DRP'])) {
                $enrollmentStatus = $grade === 'DRP' ? 'Dropped' : 'Failed';
            }
        } else {
            // Current year subjects - in progress or completed
            if ($studentStatus === 'Active') {
                // Mix of completed and in progress
                if (rand(1, 100) <= 60) {
                    $enrollmentStatus = 'Completed';
                    $grade = $this->generateGrade($student->is_irregular, $studentStatus, true);
                } else {
                    $enrollmentStatus = 'Enrolled';
                    $grade = rand(1, 100) <= 30 ? 'IP' : null;
                }
            } else {
                $enrollmentStatus = $studentStatus === 'Graduated' ? 'Completed' : 'Dropped';
                $grade = $enrollmentStatus === 'Completed' 
                    ? $this->generateGrade(false, $studentStatus, true)
                    : 'DRP';
            }
        }

        // Check if enrollment already exists
        if (Enrollment::where('student_id', $student->id)
            ->where('subject_id', $subject->id)
            ->where('academic_year_id', $academicYear->id)
            ->exists()) {
            return;
        }

        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'academic_year_id' => $academicYear->id,
            'status' => $enrollmentStatus,
            'grade' => $grade,
            'enrollment_type' => ($student->is_irregular && rand(1, 100) <= 30) ? 'retake' : 'regular',
            'submission_date' => $enrollmentStatus !== 'Enrolled' ? now()->subDays(rand(1, 90)) : null,
        ]);
    }

    private function generateGrade($isIrregular, $studentStatus, $isCompleted)
    {
        if (!$isCompleted) {
            return null;
        }

        if ($studentStatus === 'Dropped') {
            return 'DRP';
        }

        // Grade distribution (Philippine scale)
        $distribution = $isIrregular ? [
            '1.00' => 5,
            '1.25' => 8,
            '1.50' => 12,
            '1.75' => 15,
            '2.00' => 18,
            '2.25' => 15,
            '2.50' => 12,
            '2.75' => 8,
            '3.00' => 5,
            '5.00' => 2, // Failed
        ] : [
            '1.00' => 10,
            '1.25' => 15,
            '1.50' => 20,
            '1.75' => 20,
            '2.00' => 15,
            '2.25' => 10,
            '2.50' => 5,
            '2.75' => 3,
            '3.00' => 2,
            '5.00' => 0, // No fails for regular
        ];

        return $this->weightedRandom($distribution);
    }

    private function getStatusDistribution($yearLevel)
    {
        return match($yearLevel) {
            '1st Year' => [
                'Active' => 85,
                'On Leave' => 5,
                'Dropped' => 5,
                'Transferred' => 5,
            ],
            '2nd Year' => [
                'Active' => 88,
                'On Leave' => 4,
                'Dropped' => 4,
                'Transferred' => 4,
            ],
            '3rd Year' => [
                'Active' => 90,
                'On Leave' => 3,
                'Dropped' => 4,
                'Transferred' => 3,
            ],
            '4th Year' => [
                'Active' => 75,
                'Graduated' => 20,
                'On Leave' => 2,
                'Dropped' => 2,
                'Transferred' => 1,
            ],
        };
    }

    private function weightedRandom($weights)
    {
        $rand = rand(1, array_sum($weights));
        $sum = 0;
        
        foreach ($weights as $value => $weight) {
            $sum += $weight;
            if ($rand <= $sum) {
                return $value;
            }
        }
        
        return array_key_first($weights);
    }

    private function generateAddress()
    {
        $barangays = ['Poblacion', 'Tikey', 'Bobok', 'Pito', 'Karao', 'Ekip', 'Nawal', 'Daclan'];
        $barangay = $barangays[array_rand($barangays)];
        return "Brgy. {$barangay}, Bokod, Benguet";
    }

    private function getEnrollmentDate($yearLevel)
    {
        $yearNum = (int)substr($yearLevel, 0, 1);
        $yearsAgo = 4 - $yearNum; // 1st year = 3 years ago, etc.
        return now()->subYears($yearsAgo)->format('Y-m-d');
    }
}
