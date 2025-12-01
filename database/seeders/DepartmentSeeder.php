<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'College of Education',
                'code' => 'CED',
                'description' => 'College of Education',
                'is_active' => true,
            ],
            [
                'name' => 'College of Applied Technology',
                'code' => 'CAT',
                'description' => 'College of Applied Technology',
                'is_active' => true,
            ],
            [
                'name' => 'College of Criminal Justice and Education',
                'code' => 'CCJE',
                'description' => 'College of Criminal Justice and Education',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['code' => $department['code']],
                $department
            );
        }
    }
}
