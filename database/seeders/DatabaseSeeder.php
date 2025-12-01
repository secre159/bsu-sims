<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@bsu-bokod.edu.ph',
        ]);

        // Seed programs and academic years
        $this->call([
            DepartmentSeeder::class,
            ProgramSeeder::class,
            AcademicYearSeeder::class,
            SubjectSeeder::class,
            StudentSeeder::class,
            EnrollmentSeeder::class,
            ComprehensiveScenarioSeeder::class,
            SampleActivitiesSeeder::class,
        ]);
    }
}
