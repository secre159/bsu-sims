<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Map department codes to IDs
        $deptId = \App\Models\Department::pluck('id', 'code');

        $programs = [
            // College of Applied Technology (CAT)
            ['code' => 'BIT', 'name' => 'Bachelor in Industrial Technology', 'description' => 'Industrial Technology with majors in Automotive, Electrical, FSM, and GFD', 'department_id' => $deptId['CAT'] ?? null],
            ['code' => 'BSIT', 'name' => 'Bachelor of Science in Information Technology', 'description' => 'Information Technology program', 'department_id' => $deptId['CAT'] ?? null],
            ['code' => 'BSEntrep', 'name' => 'Bachelor of Science in Entrepreneurship', 'description' => 'Entrepreneurship with majors in Apparel Fashion Enterprise and Food Enterprise', 'department_id' => $deptId['CAT'] ?? null],
            ['code' => 'BPA', 'name' => 'Bachelor in Public Administration', 'description' => 'Public Administration program', 'department_id' => $deptId['CAT'] ?? null],
            
            // College of Education (CED)
            ['code' => 'BTLEd', 'name' => 'Bachelor of Technology and Livelihood Education', 'description' => 'Technology and Livelihood Education with majors in Home Economics and Industrial Arts', 'department_id' => $deptId['CED'] ?? null],
            ['code' => 'BTVTEd', 'name' => 'Bachelor of Technical-Vocational Teacher Education', 'description' => 'Technical-Vocational Teacher Education with various majors', 'department_id' => $deptId['CED'] ?? null],
            ['code' => 'BEEd', 'name' => 'Bachelor of Elementary Education', 'description' => 'Elementary Education program', 'department_id' => $deptId['CED'] ?? null],
            ['code' => 'BSEd', 'name' => 'Bachelor of Secondary Education', 'description' => 'Secondary Education with majors in English, Filipino, Mathematics, and Social Studies', 'department_id' => $deptId['CED'] ?? null],
            ['code' => 'BCAEd', 'name' => 'Bachelor of Culture and Arts Education', 'description' => 'Culture and Arts Education program', 'department_id' => $deptId['CED'] ?? null],
            
            // College of Criminal Justice and Education (CCJE)
            ['code' => 'BSCrim', 'name' => 'Bachelor of Science in Criminology', 'description' => 'Criminology program', 'department_id' => $deptId['CCJE'] ?? null],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
