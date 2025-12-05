<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class PastAcademicYearsSeeder extends Seeder
{
    /**
     * Create past academic years for realistic enrollment history
     */
    public function run(): void
    {
        $this->command->info('Creating past academic years...');

        $years = [
            ['year_code' => '2022-2023-1', 'semester' => '1st Semester', 'year_offset' => 3],
            ['year_code' => '2022-2023-2', 'semester' => '2nd Semester', 'year_offset' => 3],
            ['year_code' => '2023-2024-1', 'semester' => '1st Semester', 'year_offset' => 2],
            ['year_code' => '2023-2024-2', 'semester' => '2nd Semester', 'year_offset' => 2],
            ['year_code' => '2024-2025-1', 'semester' => '1st Semester', 'year_offset' => 1],
            ['year_code' => '2024-2025-2', 'semester' => '2nd Semester', 'year_offset' => 1],
        ];

        foreach ($years as $yearData) {
            if (AcademicYear::where('year_code', $yearData['year_code'])->exists()) {
                $this->command->warn("  • Skipped {$yearData['year_code']} - already exists");
                continue;
            }

            $baseDate = now()->subYears($yearData['year_offset'])->startOfMonth();

            AcademicYear::create([
                'year_code' => $yearData['year_code'],
                'semester' => $yearData['semester'],
                'start_date' => $baseDate,
                'end_date' => $baseDate->copy()->addMonths(6),
                'registration_start_date' => $baseDate->copy(),
                'registration_end_date' => $baseDate->copy()->addDays(30),
                'add_drop_deadline' => $baseDate->copy()->addDays(45),
                'classes_start_date' => $baseDate->copy()->addDays(46),
                'classes_end_date' => $baseDate->copy()->addMonths(5),
                'midterm_start_date' => $baseDate->copy()->addMonths(2),
                'midterm_end_date' => $baseDate->copy()->addMonths(2)->addDays(5),
                'exam_start_date' => $baseDate->copy()->addMonths(5),
                'exam_end_date' => $baseDate->copy()->addMonths(5)->addDays(5),
                'is_current' => 0,
            ]);

            $this->command->info("  ✓ Created {$yearData['year_code']}");
        }

        $this->command->info('✅ Past academic years created!');
    }
}
