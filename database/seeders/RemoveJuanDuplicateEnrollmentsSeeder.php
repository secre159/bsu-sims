<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class RemoveJuanDuplicateEnrollmentsSeeder extends Seeder
{
    /**
     * Remove Juan's duplicate enrollments in 2025-2026-1 (current semester)
     * He already completed these subjects in 2024-2025-1
     */
    public function run(): void
    {
        $this->command->info('Removing duplicate current enrollments for Juan Dela Cruz...');
        $this->command->info('');

        $juan = Student::where('student_id', 'TEST-2025-001')->first();
        
        if (!$juan) {
            $this->command->error('TEST-2025-001 not found');
            return;
        }

        // Get current academic year
        $currentYear = AcademicYear::where('year_code', '2025-2026-1')->first();
        
        if (!$currentYear) {
            $this->command->error('2025-2026-1 academic year not found');
            return;
        }

        // Get all enrollments in current semester
        $currentEnrollments = Enrollment::where('student_id', $juan->id)
            ->where('academic_year_id', $currentYear->id)
            ->with('subject')
            ->get();

        if ($currentEnrollments->isEmpty()) {
            $this->command->info('No current enrollments to remove');
            return;
        }

        $this->command->info("Found {$currentEnrollments->count()} enrollments in 2025-2026-1:");
        foreach ($currentEnrollments as $e) {
            $this->command->info("  • {$e->subject->code} - {$e->subject->name}");
        }

        $this->command->info('');
        $this->command->info('Deleting these enrollments...');

        $deleted = Enrollment::where('student_id', $juan->id)
            ->where('academic_year_id', $currentYear->id)
            ->delete();

        $this->command->info('');
        $this->command->info("✅ Removed {$deleted} duplicate enrollments");
        $this->command->info('   Juan now has a clean current semester (no active enrollments)');
        $this->command->info('   This represents a student between semesters or not yet enrolled for current term');
    }
}
