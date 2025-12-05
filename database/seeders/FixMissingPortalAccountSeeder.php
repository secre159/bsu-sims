<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FixMissingPortalAccountSeeder extends Seeder
{
    public function run(): void
    {
        $student = Student::where('student_id', 'TEST-2025-003')->first();
        
        if (!$student) {
            $this->command->error('TEST-2025-003 not found');
            return;
        }

        $existing = StudentUser::where('student_id', $student->id)->first();
        if ($existing) {
            $this->command->warn('Portal account already exists');
            return;
        }

        $last4 = substr($student->student_id, -4);
        $birthYear = date('Y', strtotime($student->birthdate));
        $password = "BSU{$last4}{$birthYear}";

        // Use TEST- prefix to ensure uniqueness
        $email = 'test.pedro.santos@student.bsu-bokod.edu.ph';
        
        StudentUser::create([
            'student_id' => $student->id,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->command->info('✓ Portal account created for TEST-2025-003');
        $this->command->info("  Email: {$email}");
        $this->command->info("  Password: {$password}");
    }
}
