<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Services\GwaCalculationService;
use Illuminate\Console\Command;

class RecalculateGwa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gwa:recalculate {--student_id= : Recalculate for a specific student ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate GWA for all students or a specific student';

    protected GwaCalculationService $gwaService;

    /**
     * Create a new command instance.
     */
    public function __construct(GwaCalculationService $gwaService)
    {
        parent::__construct();
        $this->gwaService = $gwaService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $studentId = $this->option('student_id');

        if ($studentId) {
            // Recalculate for specific student
            $student = Student::where('student_id', $studentId)->first();
            
            if (!$student) {
                $this->error("Student with ID {$studentId} not found.");
                return 1;
            }

            $this->info("Recalculating GWA for student: {$student->student_id} - {$student->first_name} {$student->last_name}");
            $this->gwaService->updateStudentStanding($student);
            $this->info("Done! GWA: " . ($student->fresh()->gwa ?? 'N/A'));
            
            return 0;
        }

        // Recalculate for all students with enrollments
        $this->info('Recalculating GWA for all students with grades...');
        
        $students = Student::whereHas('enrollments', function ($query) {
            $query->whereNotNull('grade');
        })->get();

        if ($students->isEmpty()) {
            $this->warn('No students with grades found.');
            return 0;
        }

        $progressBar = $this->output->createProgressBar($students->count());
        $progressBar->start();

        $calculated = 0;
        $skipped = 0;

        foreach ($students as $student) {
            try {
                $this->gwaService->updateStudentStanding($student);
                $calculated++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error calculating GWA for {$student->student_id}: " . $e->getMessage());
                $skipped++;
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("GWA calculation complete!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Successfully calculated', $calculated],
                ['Errors/Skipped', $skipped],
                ['Total processed', $students->count()],
            ]
        );

        return 0;
    }
}
