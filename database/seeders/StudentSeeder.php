<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Program;
use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $programs = Program::all();
        $academicYear = AcademicYear::where('year_code', '2024-2025-1')->first();

        $filipinoNames = [
            // Common Filipino surnames and first names
            ['Dela Cruz', 'Maria', 'Santos'],
            ['Garcia', 'Juan', 'Miguel'],
            ['Reyes', 'Ana', 'Marie'],
            ['Santos', 'Jose', 'Antonio'],
            ['Mendoza', 'Carmen', 'Isabel'],
            ['Rodriguez', 'Pedro', 'Luis'],
            ['Bautista', 'Rosa', 'Elena'],
            ['Gonzales', 'Miguel', 'Angel'],
            ['Lopez', 'Sofia', 'Grace'],
            ['Ramos', 'Carlos', 'Emmanuel'],
            ['Torres', 'Liza', 'Mae'],
            ['Cruz', 'Ramon', 'David'],
            ['Fernandez', 'Angela', 'Joy'],
            ['Aquino', 'Ricardo', 'James'],
            ['Castro', 'Michelle', 'Ann'],
            ['Villanueva', 'Roberto', 'Joseph'],
            ['Pascual', 'Diana', 'Rose'],
            ['Mercado', 'Edwin', 'Paul'],
            ['Santiago', 'Jenny', 'Faith'],
            ['Chavez', 'Albert', 'John'],
            ['Diaz', 'Patricia', 'Nicole'],
            ['Gutierrez', 'Francis', 'Michael'],
            ['Morales', 'Cristina', 'Mae'],
            ['Valencia', 'Antonio', 'Rey'],
            ['Rojas', 'Linda', 'Jane'],
            ['Cordova', 'Benjamin', 'Mark'],
            ['Navarro', 'Teresa', 'Lynn'],
            ['Salazar', 'Daniel', 'Joshua'],
            ['Tolentino', 'Angelica', 'Claire'],
            ['Romero', 'Victor', 'Ryan'],
            ['Miranda', 'Gemma', 'Pearl'],
            ['Aguilar', 'Manuel', 'Luis'],
            ['Alonso', 'Veronica', 'May'],
            ['Hernandez', 'Gabriel', 'Andre'],
            ['Marquez', 'Beatriz', 'Joy'],
            ['Castillo', 'Leonardo', 'Rico'],
            ['Jimenez', 'Rosalie', 'Anne'],
            ['Vargas', 'Samuel', 'Jake'],
            ['Perez', 'Melissa', 'Kate'],
            ['Ortega', 'Christopher', 'Neil'],
            ['Flores', 'Maricel', 'Faith'],
            ['Domingo', 'Ferdinand', 'James'],
            ['Velasco', 'Christine', 'Mae'],
            ['Tan', 'Vincent', 'Roy'],
            ['Lim', 'Catherine', 'Jean'],
            ['Ong', 'Kenneth', 'Paul'],
            ['Lee', 'Stephanie', 'Grace'],
            ['Sy', 'Brandon', 'Luis'],
            ['Chua', 'Jasmine', 'Rose'],
            ['Yap', 'Marcus', 'John'],
        ];

        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        $baseStatuses = ['Active', 'Active', 'Active', 'Active', 'Active', 'On Leave'];
        $genders = ['Male', 'Female'];

        $studentCount = 0;

        foreach ($filipinoNames as $index => $names) {
            $program = $programs->random();
            $yearLevel = $yearLevels[array_rand($yearLevels)];
            
            // Only allow Graduated status for 4th year students
            if ($yearLevel === '4th Year') {
                $statuses = array_merge($baseStatuses, ['Graduated']);
                $status = $statuses[array_rand($statuses)];
            } else {
                $status = $baseStatuses[array_rand($baseStatuses)];
            }
            
            $gender = str_contains($names[1], 'Maria|Ana|Carmen|Rosa|Sofia|Liza|Angela|Michelle|Diana|Jenny|Patricia|Cristina|Linda|Teresa|Angelica|Gemma|Veronica|Beatriz|Rosalie|Melissa|Maricel|Christine|Catherine|Stephanie|Jasmine') ? 'Female' : 'Male';

            $studentCount++;
            $studentId = '2024-' . str_pad($studentCount, 4, '0', STR_PAD_LEFT);

            Student::create([
                'student_id' => $studentId,
                'last_name' => $names[0],
                'first_name' => $names[1],
                'middle_name' => $names[2],
                'suffix' => null,
                'birthdate' => now()->subYears(rand(18, 25))->subDays(rand(1, 365)),
                'gender' => $gender,
                'contact_number' => '09' . rand(100000000, 999999999),
                'email' => strtolower(str_replace(' ', '.', $names[1] . '.' . $names[0])) . '@student.bsu.edu.ph',
                'address' => $this->getRandomAddress(),
                'program_id' => $program->id,
                'year_level' => $yearLevel,
                'status' => $status,
                'photo_path' => null,
                'enrollment_date' => now()->subMonths(rand(1, 24)),
                'academic_year_id' => $academicYear?->id,
                'notes' => null,
            ]);
        }
    }

    private function getRandomAddress(): string
    {
        $barangays = [
            'Poblacion',
            'Daclan',
            'Karao',
            'Bobok-Bisal',
            'Ekip',
            'Nawal',
            'Tikey',
        ];

        return $barangays[array_rand($barangays)] . ', Bokod, Benguet';
    }
}
