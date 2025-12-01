<?php

namespace Database\Seeders;

use App\Models\StudentUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestStudentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "\n=== Creating Test Student User ===\n";

        // Find the first student (2024-0001 or 2024-ACTIVE-001)
        $student = \App\Models\Student::where('student_id', '2024-0001')->first();

        if (!$student) {
            echo "[ERROR] Student 2024-0001 not found. Please run StudentSeeder first.\n";
            return;
        }

        // Check if already has account
        if (StudentUser::where('student_id', $student->id)->exists()) {
            echo "[INFO] Test account already exists. Updating password...\n";
            $studentUser = StudentUser::where('student_id', $student->id)->first();
        } else {
            echo "[✓] Creating new test account for {$student->student_id}\n";
            $studentUser = StudentUser::create([
                'student_id' => $student->id,
                'email' => $student->email,
                'password' => Hash::make('password'),
            ]);
        }

        // Update to known test password
        $studentUser->update([
            'password' => Hash::make('password')
        ]);

        echo "\n=== Test Credentials ===\n";
        echo "Student ID: 2024-0001\n";
        echo "Password: password\n";
        echo "Email: {$student->email}\n";
        echo "\nLogin URL: http://localhost:8000/student/login\n";
        echo "Dashboard URL: http://localhost:8000/student/dashboard\n\n";
    }
}
