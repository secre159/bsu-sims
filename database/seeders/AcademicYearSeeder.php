<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYears = [
            [
                'year_code' => '2024-2025-1',
                'semester' => '1st Semester',
                'start_date' => '2024-08-01',
                'end_date' => '2024-12-31',
                'is_current' => true,
                // Registration: Aug 1-15
                'registration_start_date' => '2024-08-01',
                'registration_end_date' => '2024-08-15',
                'add_drop_deadline' => '2024-08-29', // 2 weeks after classes start
                'classes_start_date' => '2024-08-22',
                'classes_end_date' => '2024-12-15',
                'midterm_start_date' => '2024-10-01',
                'midterm_end_date' => '2024-10-15',
                'exam_start_date' => '2024-12-02',
                'exam_end_date' => '2024-12-20',
            ],
            [
                'year_code' => '2024-2025-2',
                'semester' => '2nd Semester',
                'start_date' => '2025-01-01',
                'end_date' => '2025-05-31',
                'is_current' => false,
                // Registration: Dec 15-Jan 5
                'registration_start_date' => '2024-12-15',
                'registration_end_date' => '2025-01-05',
                'add_drop_deadline' => '2025-01-26',
                'classes_start_date' => '2025-01-15',
                'classes_end_date' => '2025-05-10',
                'midterm_start_date' => '2025-03-01',
                'midterm_end_date' => '2025-03-15',
                'exam_start_date' => '2025-05-01',
                'exam_end_date' => '2025-05-20',
            ],
        ];

        foreach ($academicYears as $year) {
            AcademicYear::create($year);
        }
    }
}
