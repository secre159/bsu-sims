<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Program;
use App\Models\Activity;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;

class SampleActivitiesSeeder extends Seeder
{
    /**
     * Seed sample activities for testing audit trail
     */
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            echo "\n[SampleActivitiesSeeder] No user found. Skipping.\n";
            return;
        }

        echo "\n=== Seeding Sample Activities ===\n";

        // Get test data
        $activeStudent = Student::where('student_id', '2024-ACTIVE-001')->first();
        $dropoutStudent = Student::where('student_id', '2024-DROPOUT-001')->first();
        $failedStudent = Student::where('student_id', '2024-FAILED-001')->first();
        $completedStudent = Student::where('student_id', '2024-COMPLETED-001')->first();
        $graduatedStudent = Student::where('student_id', '2024-GRAD-001')->first();
        $progressStudent = Student::where('student_id', '2024-PROGRESS-001')->first();

        // Log student creation activities
        foreach ([$activeStudent, $dropoutStudent, $failedStudent, $completedStudent, $graduatedStudent, $progressStudent] as $student) {
            if ($student) {
                Activity::create([
                    'user_id' => $user->id,
                    'subject_type' => 'App\\Models\\Student',
                    'subject_id' => $student->id,
                    'action' => 'created',
                    'description' => "Student {$student->student_id} - {$student->first_name} {$student->last_name} created ({$student->status})",
                    'properties' => [
                        'student_id' => $student->student_id,
                        'program_id' => $student->program_id,
                        'year_level' => $student->year_level,
                        'status' => $student->status,
                    ],
                ]);
                echo "[✓] Logged creation of student: {$student->student_id}\n";
            }
        }

        // Log enrollment activities
        $enrollments = Enrollment::with('student', 'subject')->limit(15)->get();
        foreach ($enrollments as $enrollment) {
            if ($enrollment->status === 'Enrolled') {
                Activity::create([
                    'user_id' => $user->id,
                    'subject_type' => 'App\\Models\\Enrollment',
                    'subject_id' => $enrollment->id,
                    'action' => 'enrolled',
                    'description' => "Student {$enrollment->student->student_id} enrolled in {$enrollment->subject->code} - {$enrollment->subject->name}",
                    'properties' => [
                        'student_id' => $enrollment->student_id,
                        'subject_id' => $enrollment->subject_id,
                        'academic_year_id' => $enrollment->academic_year_id,
                        'status' => 'Enrolled',
                    ],
                ]);
            } elseif ($enrollment->status === 'Dropped') {
                Activity::create([
                    'user_id' => $user->id,
                    'subject_type' => 'App\\Models\\Enrollment',
                    'subject_id' => $enrollment->id,
                    'action' => 'dropped',
                    'description' => "Student {$enrollment->student->student_id} dropped {$enrollment->subject->code}",
                    'properties' => [
                        'student_id' => $enrollment->student_id,
                        'subject_id' => $enrollment->subject_id,
                        'reason' => 'Student requested drop',
                    ],
                ]);
            } elseif ($enrollment->status === 'Completed') {
                Activity::create([
                    'user_id' => $user->id,
                    'subject_type' => 'App\\Models\\Enrollment',
                    'subject_id' => $enrollment->id,
                    'action' => 'completed',
                    'description' => "Student {$enrollment->student->student_id} completed {$enrollment->subject->code} with grade {$enrollment->grade}",
                    'properties' => [
                        'student_id' => $enrollment->student_id,
                        'subject_id' => $enrollment->subject_id,
                        'grade' => $enrollment->grade,
                    ],
                ]);
            } elseif ($enrollment->status === 'Failed') {
                Activity::create([
                    'user_id' => $user->id,
                    'subject_type' => 'App\\Models\\Enrollment',
                    'subject_id' => $enrollment->id,
                    'action' => 'failed',
                    'description' => "Student {$enrollment->student->student_id} failed {$enrollment->subject->code} with grade {$enrollment->grade}",
                    'properties' => [
                        'student_id' => $enrollment->student_id,
                        'subject_id' => $enrollment->subject_id,
                        'grade' => $enrollment->grade,
                        'needs_retake' => true,
                    ],
                ]);
            }
        }
        echo "[✓] Logged " . $enrollments->count() . " enrollment activities\n";

        // Log subject creation activities
        $subjects = Subject::take(5)->get();
        foreach ($subjects as $subject) {
            Activity::create([
                'user_id' => $user->id,
                'subject_type' => 'App\\Models\\Subject',
                'subject_id' => $subject->id,
                'action' => 'created',
                'description' => "Subject {$subject->code} - {$subject->name} created",
                'properties' => [
                    'code' => $subject->code,
                    'program_id' => $subject->program_id,
                    'year_level' => $subject->year_level,
                    'units' => $subject->units,
                ],
            ]);
        }
        echo "[✓] Logged subject creation activities\n";

        // Log subject deactivation
        $inactiveSubject = Subject::where('is_active', false)->first();
        if ($inactiveSubject) {
            Activity::create([
                'user_id' => $user->id,
                'subject_type' => 'App\\Models\\Subject',
                'subject_id' => $inactiveSubject->id,
                'action' => 'updated',
                'description' => "Subject {$inactiveSubject->code} deactivated",
                'properties' => [
                    'changes' => [
                        'is_active' => [
                            'old' => true,
                            'new' => false,
                        ],
                    ],
                ],
            ]);
            echo "[✓] Logged subject deactivation: {$inactiveSubject->code}\n";
        }

        // Log program creation activities
        $programs = Program::take(3)->get();
        foreach ($programs as $program) {
            Activity::create([
                'user_id' => $user->id,
                'subject_type' => 'App\\Models\\Program',
                'subject_id' => $program->id,
                'action' => 'created',
                'description' => "Program {$program->code} - {$program->name} created",
                'properties' => ['code' => $program->code],
            ]);
        }
        echo "[✓] Logged program creation activities\n";

        // Log status transitions
        if ($progressStudent) {
            Activity::create([
                'user_id' => $user->id,
                'subject_type' => 'App\\Models\\Student',
                'subject_id' => $progressStudent->id,
                'action' => 'updated',
                'description' => "Student {$progressStudent->student_id} status updated: Active → Graduated",
                'properties' => [
                    'changes' => [
                        'status' => [
                            'old' => 'Active',
                            'new' => 'Graduated',
                        ],
                    ],
                ],
            ]);
            echo "[✓] Logged status transition\n";

            Activity::create([
                'user_id' => $user->id,
                'subject_type' => 'App\\Models\\Student',
                'subject_id' => $progressStudent->id,
                'action' => 'updated',
                'description' => "Student {$progressStudent->student_id} status updated: Graduated → Active",
                'properties' => [
                    'changes' => [
                        'status' => [
                            'old' => 'Graduated',
                            'new' => 'Active',
                        ],
                    ],
                ],
            ]);
            echo "[✓] Logged reverse status transition\n";
        }

        echo "\n=== Sample Activities Seeding Complete ===\n";
        echo "Total activities should now be visible in /activities page\n\n";
    }
}
