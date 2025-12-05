<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Program;

class SampleStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = Program::all();

        if ($programs->isEmpty()) {
            $this->command->error('No programs found. Please run ProgramSeeder first.');
            return;
        }

        $students = [
            // 1st Year Students
            [
                'student_id' => '2024-0001',
                'first_name' => 'Juan',
                'middle_name' => 'Santos',
                'last_name' => 'Dela Cruz',
                'suffix' => null,
                'email' => 'juan.delacruz@bsu.edu.ph',
                'contact_number' => '09171234567',
                'year_level' => '1st Year',
                'status' => 'Active',
            ],
            [
                'student_id' => '2024-0002',
                'first_name' => 'Maria',
                'middle_name' => 'Garcia',
                'last_name' => 'Santos',
                'suffix' => null,
                'email' => 'maria.santos@bsu.edu.ph',
                'contact_number' => '09181234567',
                'year_level' => '1st Year',
                'status' => 'Active',
            ],
            [
                'student_id' => '2024-0003',
                'first_name' => 'Pedro',
                'middle_name' => 'Reyes',
                'last_name' => 'Bautista',
                'suffix' => 'Jr.',
                'email' => 'pedro.bautista@bsu.edu.ph',
                'contact_number' => '09191234567',
                'year_level' => '1st Year',
                'status' => 'Active',
            ],
            
            // 2nd Year Students
            [
                'student_id' => '2023-0001',
                'first_name' => 'Ana',
                'middle_name' => 'Lopez',
                'last_name' => 'Ramos',
                'suffix' => null,
                'email' => 'ana.ramos@bsu.edu.ph',
                'contact_number' => '09201234567',
                'year_level' => '2nd Year',
                'status' => 'Active',
            ],
            [
                'student_id' => '2023-0002',
                'first_name' => 'Carlos',
                'middle_name' => 'Torres',
                'last_name' => 'Mendoza',
                'suffix' => null,
                'email' => 'carlos.mendoza@bsu.edu.ph',
                'contact_number' => '09211234567',
                'year_level' => '2nd Year',
                'status' => 'Active',
            ],
            [
                'student_id' => '2023-0003',
                'first_name' => 'Elena',
                'middle_name' => 'Cruz',
                'last_name' => 'Villanueva',
                'suffix' => null,
                'email' => 'elena.villanueva@bsu.edu.ph',
                'contact_number' => '09221234567',
                'year_level' => '2nd Year',
                'status' => 'Active',
            ],

            // 3rd Year Students
            [
                'student_id' => '2022-0001',
                'first_name' => 'Roberto',
                'middle_name' => 'Aquino',
                'last_name' => 'Fernandez',
                'suffix' => null,
                'email' => 'roberto.fernandez@bsu.edu.ph',
                'contact_number' => '09231234567',
                'year_level' => '3rd Year',
                'status' => 'Active',
            ],
            [
                'student_id' => '2022-0002',
                'first_name' => 'Sofia',
                'middle_name' => 'Morales',
                'last_name' => 'Castillo',
                'suffix' => null,
                'email' => 'sofia.castillo@bsu.edu.ph',
                'contact_number' => '09241234567',
                'year_level' => '3rd Year',
                'status' => 'Active',
            ],
            [
                'student_id' => '2022-0003',
                'first_name' => 'Miguel',
                'middle_name' => 'Navarro',
                'last_name' => 'Gutierrez',
                'suffix' => null,
                'email' => 'miguel.gutierrez@bsu.edu.ph',
                'contact_number' => '09251234567',
                'year_level' => '3rd Year',
                'status' => 'Active',
            ],

            // 4th Year Students
            [
                'student_id' => '2021-0001',
                'first_name' => 'Isabella',
                'middle_name' => 'Rivera',
                'last_name' => 'Ortiz',
                'suffix' => null,
                'email' => 'isabella.ortiz@bsu.edu.ph',
                'contact_number' => '09261234567',
                'year_level' => '4th Year',
                'status' => 'Active',
            ],
            [
                'student_id' => '2021-0002',
                'first_name' => 'Gabriel',
                'middle_name' => 'Herrera',
                'last_name' => 'Jimenez',
                'suffix' => null,
                'email' => 'gabriel.jimenez@bsu.edu.ph',
                'contact_number' => '09271234567',
                'year_level' => '4th Year',
                'status' => 'Active',
            ],
            [
                'student_id' => '2021-0003',
                'first_name' => 'Camila',
                'middle_name' => 'Flores',
                'last_name' => 'Romero',
                'suffix' => null,
                'email' => 'camila.romero@bsu.edu.ph',
                'contact_number' => '09281234567',
                'year_level' => '4th Year',
                'status' => 'Active',
            ],

            // Inactive Student
            [
                'student_id' => '2022-0004',
                'first_name' => 'Diego',
                'middle_name' => 'Alvarez',
                'last_name' => 'Medina',
                'suffix' => null,
                'email' => 'diego.medina@bsu.edu.ph',
                'contact_number' => '09291234567',
                'year_level' => '3rd Year',
                'status' => 'Inactive',
            ],

            // Graduated Student
            [
                'student_id' => '2020-0001',
                'first_name' => 'Lucia',
                'middle_name' => 'Vargas',
                'last_name' => 'Silva',
                'suffix' => null,
                'email' => 'lucia.silva@bsu.edu.ph',
                'contact_number' => '09301234567',
                'year_level' => '4th Year',
                'status' => 'Graduated',
            ],
        ];

        $this->command->info('Creating sample students...');

        foreach ($students as $index => $studentData) {
            // Distribute students across different programs
            $program = $programs[$index % $programs->count()];
            
            $studentData['program_id'] = $program->id;
            $studentData['birthdate'] = now()->subYears(18 + rand(0, 5))->subMonths(rand(0, 11))->format('Y-m-d');
            $studentData['address'] = 'Bokod, Benguet';
            $studentData['gender'] = rand(0, 1) ? 'Male' : 'Female';
            
            Student::create($studentData);
            
            $this->command->info("Created: {$studentData['first_name']} {$studentData['last_name']} ({$studentData['student_id']})");
        }

        $this->command->info('Sample students seeded successfully!');
        $this->command->info('Total students created: ' . count($students));
    }
}
