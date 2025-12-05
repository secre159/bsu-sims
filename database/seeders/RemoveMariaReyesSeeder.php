<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentUser;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;

class RemoveMariaReyesSeeder extends Seeder
{
    /**
     * Remove Maria Reyes (TEST-2025-002) from the system
     */
    public function run(): void
    {
        $this->command->info('Removing Maria Reyes (TEST-2025-002)...');
        $this->command->info('');

        $maria = Student::where('student_id', 'TEST-2025-002')->first();
        
        if (!$maria) {
            $this->command->warn('TEST-2025-002 not found - already removed or never existed');
            return;
        }

        $this->command->info("Found: {$maria->full_name}");
        $this->command->info("  Student ID: {$maria->student_id}");
        $this->command->info("  Program: {$maria->program->name}");
        $this->command->info("  Year Level: {$maria->year_level}");
        $this->command->info('');

        // Count related records
        $enrollmentCount = Enrollment::where('student_id', $maria->id)->count();
        $portalUser = StudentUser::where('student_id', $maria->id)->first();

        $this->command->info("Deleting related records:");
        $this->command->info("  • Enrollments: {$enrollmentCount}");
        $this->command->info("  • Portal account: " . ($portalUser ? "Yes ({$portalUser->email})" : "No"));
        $this->command->info('');

        // Delete enrollments (will cascade due to foreign key)
        Enrollment::where('student_id', $maria->id)->delete();
        $this->command->info("  ✓ Deleted {$enrollmentCount} enrollments");

        // Delete portal account
        if ($portalUser) {
            $portalUser->delete();
            $this->command->info("  ✓ Deleted portal account");
        }

        // Delete student record
        $maria->delete();
        $this->command->info("  ✓ Deleted student record");

        $this->command->info('');
        $this->command->info('✅ Maria Reyes (TEST-2025-002) has been removed from the system');
    }
}
