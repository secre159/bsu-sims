<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\StudentUser;
use App\Models\Program;
use Illuminate\Support\Facades\Hash;

class SampleStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $program = Program::first();
        
        if (!$program) {
            $this->command->error('No programs found. Please create a program first.');
            return;
        }

        // Check if student already exists
        $existingStudent = Student::where('student_id', '2024-SAMPLE-001')->first();
        
        if ($existingStudent) {
            $this->command->warn('Student with ID 2024-SAMPLE-001 already exists. Skipping...');
            return;
        }

        $student = Student::create([
            'student_id' => '2024-SAMPLE-001',
            'first_name' => 'Juan',
            'middle_name' => 'Cruz',
            'last_name' => 'Dela Cruz',
            'email_address' => 'juan.delacruz@sample.edu.ph',
            'birthdate' => '2000-01-01',
            'gender' => 'Male',
            'program_id' => $program->id,
            'year_level' => '1st Year',
            'status' => 'Active',
            'contact_number' => '09123456789',
            'home_address' => 'Sample Address, Bokod, Benguet',
            'citizenship' => 'Filipino',
        ]);

        // Create StudentUser for login
        $studentUser = StudentUser::create([
            'student_id' => $student->id,
            'email' => $student->email_address,
            'password' => Hash::make('password'),
        ]);

        $this->command->info('✓ Sample student created successfully!');
        $this->command->info('  Student ID: ' . $student->student_id);
        $this->command->info('  Name: ' . $student->first_name . ' ' . $student->last_name);
        $this->command->info('  Email: ' . $student->email_address);
        $this->command->info('  Password: password');
        $this->command->info('  Program: ' . $program->name);
        $this->command->info('');
        $this->command->info('Login at: http://127.0.0.1:8000/student/login');
    }
}
