<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\AcademicYear;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class ComprehensiveScenarioSeeder extends Seeder
{
    /**
     * Seed comprehensive test scenarios covering:
     * - Students with different statuses (Active, On Leave, Graduated, Dropped)
     * - Enrollments in different states (Enrolled, Completed, Dropped, Failed)
     * - Prerequisites being met or not met
     * - Subject activations and deactivations
     * - Program changes
     * - Audit trail logging
     */
    public function run(): void
    {
        // Set a user for audit logging and create activities manually
        $user = User::first();
        if (!$user) {
            echo "\n[ComprehensiveScenarioSeeder] Error: No user found. Skipping audit logging.\n";
            $user = null;
        } else {
            Auth::login($user);
        }

        $academicYear = AcademicYear::where('is_current', true)->first();
        if (!$academicYear) {
            $academicYear = AcademicYear::where('year_code', 'LIKE', '2024-2025%')->first();
        }
        if (!$academicYear) {
            echo "\n[ComprehensiveScenarioSeeder] Warning: No active academic year found. Skipping.\n";
            return;
        }

        $program = Program::where('is_active', true)->first();
        if (!$program) {
            echo "\n[ComprehensiveScenarioSeeder] Warning: No active program found. Skipping.\n";
            return;
        }

        echo "\n=== Seeding Comprehensive Scenarios ===\n";

        // Get subjects and ensure we have prerequisites set up
        $subjects = Subject::where('program_id', $program->id)->get();
        
        // Set up prerequisite chain: CS101 -> CS102 -> CS201
        $cs101 = $subjects->where('code', 'CS101')->first();
        $cs102 = $subjects->where('code', 'CS102')->first();
        $cs201 = $subjects->where('code', 'CS201')->first();

        if ($cs102 && $cs101) {
            $cs102->update(['prerequisite_subject_ids' => [$cs101->id]]);
            echo "[✓] Set CS102 prerequisite: CS101\n";
        }

        if ($cs201 && $cs102) {
            $cs201->update(['prerequisite_subject_ids' => [$cs102->id]]);
            echo "[✓] Set CS201 prerequisite: CS102\n";
        }

        // === SCENARIO 1: Active Student with Full Enrollment ===
        echo "\n--- Scenario 1: Active Student (Fully Enrolled) ---\n";
        $activeStudent = Student::create([
            'student_id' => '2024-ACTIVE-001',
            'last_name' => 'Active',
            'first_name' => 'Scholar',
            'middle_name' => 'Test',
            'birthdate' => now()->subYears(20),
            'gender' => 'Male',
            'contact_number' => '09171234567',
            'email' => 'active.scholar@student.bsu.edu.ph',
            'address' => 'Poblacion, Bokod, Benguet',
            'program_id' => $program->id,
            'year_level' => '1st Year',
            'status' => 'Active',
            'enrollment_date' => now()->subMonths(6),
            'academic_year_id' => $academicYear->id,
        ]);
        echo "[✓] Created active student: {$activeStudent->student_id}\n";
        if ($user) {
            Activity::create([
                'user_id' => $user->id,
                'subject_type' => 'App\\Models\\Student',
                'subject_id' => $activeStudent->id,
                'action' => 'created',
                'description' => "Student {$activeStudent->student_id} - {$activeStudent->first_name} {$activeStudent->last_name} created",
                'properties' => ['student_id' => $activeStudent->student_id, 'program_id' => $program->id],
            ]);
        }

        // Enroll in 4 subjects
        $enrollmentSubjects = $subjects->where('year_level', '1st Year')->take(4);
        foreach ($enrollmentSubjects as $subject) {
            $enrollment = Enrollment::create([
                'student_id' => $activeStudent->id,
                'subject_id' => $subject->id,
                'academic_year_id' => $academicYear->id,
                'status' => 'Enrolled',
                'grade' => null,
            ]);
            if ($user) {
                Activity::create([
                    'user_id' => $user->id,
                    'subject_type' => 'App\\Models\\Enrollment',
                    'subject_id' => $enrollment->id,
                    'action' => 'enrolled',
                    'description' => "Student {$activeStudent->student_id} enrolled in {$subject->code}",
                    'properties' => ['student_id' => $activeStudent->id, 'subject_id' => $subject->id],
                ]);
            }
        }
        echo "[✓] Enrolled in 4 subjects\n";

        // === SCENARIO 2: Student Who Dropped a Course ===
        echo "\n--- Scenario 2: Student with Dropped Course ---\n";
        $dropoutStudent = Student::create([
            'student_id' => '2024-DROPOUT-001',
            'last_name' => 'Quitter',
            'first_name' => 'CourseD',
            'middle_name' => 'Test',
            'birthdate' => now()->subYears(20),
            'gender' => 'Female',
            'contact_number' => '09171234568',
            'email' => 'dropout.course@student.bsu.edu.ph',
            'address' => 'Daclan, Bokod, Benguet',
            'program_id' => $program->id,
            'year_level' => '1st Year',
            'status' => 'Active',
            'enrollment_date' => now()->subMonths(5),
            'academic_year_id' => $academicYear->id,
        ]);
        echo "[✓] Created student: {$dropoutStudent->student_id}\n";
        if ($user) {
            Activity::create([
                'user_id' => $user->id,
                'subject_type' => 'App\\Models\\Student',
                'subject_id' => $dropoutStudent->id,
                'action' => 'created',
                'description' => "Student {$dropoutStudent->student_id} - {$dropoutStudent->first_name} {$dropoutStudent->last_name} created",
                'properties' => ['student_id' => $dropoutStudent->student_id],
            ]);
        }

        // Enroll and drop one subject
        $enrollmentSubjects2 = $subjects->where('year_level', '1st Year')->skip(1)->take(5);
        foreach ($enrollmentSubjects2 as $index => $subject) {
            if ($index === 0) {
                // This one will be dropped
                $enrollment = Enrollment::create([
                    'student_id' => $dropoutStudent->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $academicYear->id,
                    'status' => 'Dropped',
                    'grade' => null,
                    'remarks' => 'Dropped on ' . now()->format('Y-m-d H:i'),
                ]);
                if ($user) {
                    Activity::create([
                        'user_id' => $user->id,
                        'subject_type' => 'App\\Models\\Enrollment',
                        'subject_id' => $enrollment->id,
                        'action' => 'dropped',
                        'description' => "Student {$dropoutStudent->student_id} dropped {$subject->code}",
                        'properties' => ['student_id' => $dropoutStudent->id, 'subject_id' => $subject->id],
                    ]);
                }
                echo "[✓] Enrolled and dropped from: {$subject->code}\n";
            } else {
                $enrollment = Enrollment::create([
                    'student_id' => $dropoutStudent->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $academicYear->id,
                    'status' => 'Enrolled',
                ]);
                if ($user) {
                    Activity::create([
                        'user_id' => $user->id,
                        'subject_type' => 'App\\Models\\Enrollment',
                        'subject_id' => $enrollment->id,
                        'action' => 'enrolled',
                        'description' => "Student {$dropoutStudent->student_id} enrolled in {$subject->code}",
                        'properties' => ['student_id' => $dropoutStudent->id, 'subject_id' => $subject->id],
                    ]);
                }
            }
        }

        // === SCENARIO 3: Student with Failed Grade ===
        echo "\n--- Scenario 3: Student with Failed Course ---\n";
        $failedStudent = Student::create([
            'student_id' => '2024-FAILED-001',
            'last_name' => 'Flunked',
            'first_name' => 'UnluckyD',
            'middle_name' => 'Test',
            'birthdate' => now()->subYears(21),
            'gender' => 'Male',
            'contact_number' => '09171234569',
            'email' => 'flunked.course@student.bsu.edu.ph',
            'address' => 'Karao, Bokod, Benguet',
            'program_id' => $program->id,
            'year_level' => '2nd Year',
            'status' => 'Active',
            'enrollment_date' => now()->subMonths(4),
            'academic_year_id' => $academicYear->id,
        ]);
        echo "[✓] Created student: {$failedStudent->student_id}\n";

        $year2Subjects = $subjects->where('year_level', '2nd Year')->take(4);
        foreach ($year2Subjects as $index => $subject) {
            if ($index === 0) {
                // Failed grade
                Enrollment::create([
                    'student_id' => $failedStudent->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $academicYear->id,
                    'status' => 'Failed',
                    'grade' => '4.0',
                    'remarks' => 'Failed - needs retake',
                ]);
                echo "[✓] Failed: {$subject->code} (Grade: 4.0)\n";
            } else {
                Enrollment::create([
                    'student_id' => $failedStudent->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $academicYear->id,
                    'status' => 'Enrolled',
                ]);
            }
        }

        // === SCENARIO 4: Student with Completed Courses (Previous Semester) ===
        echo "\n--- Scenario 4: Student with Completed Courses ---\n";
        $completedStudent = Student::create([
            'student_id' => '2024-COMPLETED-001',
            'last_name' => 'Finished',
            'first_name' => 'Course',
            'middle_name' => 'Test',
            'birthdate' => now()->subYears(21),
            'gender' => 'Female',
            'contact_number' => '09171234570',
            'email' => 'finished.course@student.bsu.edu.ph',
            'address' => 'Bobok-Bisal, Bokod, Benguet',
            'program_id' => $program->id,
            'year_level' => '2nd Year',
            'status' => 'Active',
            'enrollment_date' => now()->subMonths(12),
            'academic_year_id' => $academicYear->id,
        ]);
        echo "[✓] Created student: {$completedStudent->student_id}\n";

        // Completed courses with grades
        $completedSubjects = $subjects->where('year_level', '1st Year')->take(4);
        foreach ($completedSubjects as $index => $subject) {
            Enrollment::create([
                'student_id' => $completedStudent->id,
                'subject_id' => $subject->id,
                'academic_year_id' => $academicYear->id,
                'status' => 'Completed',
                'grade' => ['1.0', '1.25', '1.5', '2.0'][$index] ?? '2.0',
            ]);
        }
        echo "[✓] Enrolled with completed courses (passed prerequisites)\n";

        // Now enroll in advanced course that requires prerequisites
        if ($cs102) {
            Enrollment::create([
                'student_id' => $completedStudent->id,
                'subject_id' => $cs102->id,
                'academic_year_id' => $academicYear->id,
                'status' => 'Enrolled',
            ]);
            echo "[✓] Enrolled in CS102 (has CS101 prerequisite)\n";
        }

        // === SCENARIO 5: Student On Leave ===
        echo "\n--- Scenario 5: Student on Leave ---\n";
        $leaveStudent = Student::create([
            'student_id' => '2024-LEAVE-001',
            'last_name' => 'Absent',
            'first_name' => 'OnLeave',
            'middle_name' => 'Test',
            'birthdate' => now()->subYears(22),
            'gender' => 'Male',
            'contact_number' => '09171234571',
            'email' => 'onleave@student.bsu.edu.ph',
            'address' => 'Ekip, Bokod, Benguet',
            'program_id' => $program->id,
            'year_level' => '3rd Year',
            'status' => 'On Leave',
            'enrollment_date' => now()->subMonths(18),
            'academic_year_id' => $academicYear->id,
        ]);
        echo "[✓] Created student on leave: {$leaveStudent->student_id}\n";

        // No current enrollments (on leave)
        echo "[✓] No current enrollments (on leave)\n";

        // === SCENARIO 6: Graduated Student ===
        echo "\n--- Scenario 6: Graduated Student ---\n";
        $graduatedStudent = Student::create([
            'student_id' => '2024-GRAD-001',
            'last_name' => 'Valedictorian',
            'first_name' => 'Graduate',
            'middle_name' => 'Test',
            'birthdate' => now()->subYears(24),
            'gender' => 'Female',
            'contact_number' => '09171234572',
            'email' => 'graduate@student.bsu.edu.ph',
            'address' => 'Nawal, Bokod, Benguet',
            'program_id' => $program->id,
            'year_level' => '4th Year',
            'status' => 'Graduated',
            'enrollment_date' => now()->subMonths(48),
            'academic_year_id' => $academicYear->id,
        ]);
        echo "[✓] Created graduated student: {$graduatedStudent->student_id}\n";

        // Has completed all courses
        $allSubjects = $subjects->take(10);
        foreach ($allSubjects as $index => $subject) {
            Enrollment::create([
                'student_id' => $graduatedStudent->id,
                'subject_id' => $subject->id,
                'academic_year_id' => $academicYear->id,
                'status' => 'Completed',
                'grade' => ['1.0', '1.0', '1.25', '1.25', '1.5', '1.5', '1.75', '1.75', '2.0', '2.0'][$index] ?? '2.0',
            ]);
        }
        echo "[✓] Completed 10 courses\n";

        // === SCENARIO 7: Dropped Student ===
        echo "\n--- Scenario 7: Dropped Student ---\n";
        $droppedStudent = Student::create([
            'student_id' => '2024-DROPPED-001',
            'last_name' => 'Discontinued',
            'first_name' => 'Program',
            'middle_name' => 'Test',
            'birthdate' => now()->subYears(20),
            'gender' => 'Male',
            'contact_number' => '09171234573',
            'email' => 'dropped@student.bsu.edu.ph',
            'address' => 'Tikey, Bokod, Benguet',
            'program_id' => $program->id,
            'year_level' => '1st Year',
            'status' => 'Dropped',
            'enrollment_date' => now()->subMonths(3),
            'academic_year_id' => $academicYear->id,
        ]);
        echo "[✓] Created dropped student: {$droppedStudent->student_id}\n";

        // Had some enrollments before dropping
        $dropSubjects = $subjects->where('year_level', '1st Year')->take(2);
        foreach ($dropSubjects as $subject) {
            Enrollment::create([
                'student_id' => $droppedStudent->id,
                'subject_id' => $subject->id,
                'academic_year_id' => $academicYear->id,
                'status' => 'Dropped',
                'remarks' => 'Dropped on ' . now()->subDays(10)->format('Y-m-d H:i'),
            ]);
        }
        echo "[✓] Had partial enrollment before dropping\n";

        // === SCENARIO 8: Different Year Levels ===
        echo "\n--- Scenario 8: Students across all year levels ---\n";
foreach (['2nd Year', '3rd Year', '4th Year'] as $yearLevel) {
            $yearStudent = Student::create([
                'student_id' => '2024-' . strtoupper(str_replace(' ', '', $yearLevel)) . '-001',
                'last_name' => 'Year' . $yearLevel,
                'first_name' => 'Level',
                'middle_name' => 'Test',
                'birthdate' => now()->subYears(rand(19, 25)),
                'gender' => rand(0, 1) ? 'Male' : 'Female',
                'contact_number' => '0917' . rand(10000000, 99999999),
                'email' => 'year' . str_replace(' ', '', $yearLevel) . '@student.bsu.edu.ph',
                'address' => 'Poblacion, Bokod, Benguet',
                'program_id' => $program->id,
                'year_level' => $yearLevel,
                'status' => 'Active',
                'enrollment_date' => now()->subMonths(rand(6, 36)),
                'academic_year_id' => $academicYear->id,
            ]);

            // Enroll in appropriate level subjects
            $levelSubjects = $subjects->where('year_level', $yearLevel)->take(4);
            foreach ($levelSubjects as $subject) {
                Enrollment::create([
                    'student_id' => $yearStudent->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $academicYear->id,
                    'status' => 'Enrolled',
                ]);
            }

            echo "[✓] Created {$yearLevel} student: {$yearStudent->student_id}\n";
        }

        // === SCENARIO 9: Test Inactive Subject (Blocks New Enrollments) ===
        echo "\n--- Scenario 9: Inactive Subject Handling ---\n";
        $inactiveSubject = $subjects->first();
        if ($inactiveSubject) {
            $inactiveSubject->update(['is_active' => false]);
            echo "[✓] Marked subject inactive: {$inactiveSubject->code}\n";
        }

        // === SCENARIO 10: Student Status Progression ===
        echo "\n--- Scenario 10: Student Status Progression Log ---\n";
        $progressStudent = Student::create([
            'student_id' => '2024-PROGRESS-001',
            'last_name' => 'Progressor',
            'first_name' => 'Career',
            'middle_name' => 'Test',
            'birthdate' => now()->subYears(23),
            'gender' => 'Male',
            'contact_number' => '09171234574',
            'email' => 'progress@student.bsu.edu.ph',
            'address' => 'Poblacion, Bokod, Benguet',
            'program_id' => $program->id,
            'year_level' => '4th Year',
            'status' => 'Active',
            'enrollment_date' => now()->subMonths(36),
            'academic_year_id' => $academicYear->id,
        ]);
        echo "[✓] Created progression student: {$progressStudent->student_id}\n";

        // Update status to simulate progression
        $progressStudent->update(['status' => 'Graduated']);
        $progressStudent->update(['status' => 'Active']);
        echo "[✓] Status updates logged for audit trail\n";

        echo "\n=== Comprehensive Scenario Seeding Complete ===\n";
        echo "Total Scenarios Created: 10\n";
        echo "Check Activity table to verify audit logging of all operations.\n\n";
    }
}
