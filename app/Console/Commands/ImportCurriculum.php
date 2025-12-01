<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subject;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

class ImportCurriculum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'curriculum:import {--file=curriculum_parsed.json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import parsed curriculum data into database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fileName = $this->option('file');
        $filePath = storage_path("app/{$fileName}");
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            $this->info("Run 'php artisan curriculum:parse <pdf_path>' first");
            return 1;
        }

        $this->info("Reading curriculum data from: {$filePath}");
        
        $coursesData = json_decode(file_get_contents($filePath), true);
        
        if (!$coursesData) {
            $this->error("Invalid JSON file");
            return 1;
        }

        $this->info("Found " . count($coursesData) . " courses to import");
        
        if (!$this->confirm('Do you want to proceed with import?', true)) {
            $this->info('Import cancelled');
            return 0;
        }

        // Get all programs for mapping
        $programs = Program::all()->keyBy('name');
        
        $imported = 0;
        $skipped = 0;
        $errors = [];

        DB::beginTransaction();
        
        try {
            $progressBar = $this->output->createProgressBar(count($coursesData));
            $progressBar->start();
            
            foreach ($coursesData as $courseData) {
                // Try to find matching program
                $programId = $this->findProgramId($courseData['program'], $programs);
                
                if (!$programId) {
                    $errors[] = "Program not found for: {$courseData['program']} - {$courseData['code']}";
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }
                
                // Check if subject already exists
                $existing = Subject::where('code', $courseData['code'])
                    ->where('program_id', $programId)
                    ->first();
                
                if ($existing) {
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }
                
                // Create new subject
                Subject::create([
                    'code' => $courseData['code'],
                    'name' => $courseData['name'],
                    'description' => "Imported from curriculum - {$courseData['program']}",
                    'units' => $courseData['units'],
                    'program_id' => $programId,
                    'year_level' => $courseData['year_level'],
                    'semester' => $courseData['semester'],
                    'is_active' => true,
                ]);
                
                $imported++;
                $progressBar->advance();
            }
            
            $progressBar->finish();
            $this->newLine(2);
            
            DB::commit();
            
            $this->info("\n✓ Import completed!");
            $this->info("  Imported: {$imported}");
            $this->info("  Skipped: {$skipped}");
            
            if (count($errors) > 0) {
                $this->warn("\nErrors encountered:");
                foreach (array_slice($errors, 0, 10) as $error) {
                    $this->warn("  - {$error}");
                }
                if (count($errors) > 10) {
                    $this->warn("  ... and " . (count($errors) - 10) . " more");
                }
            }
            
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error importing curriculum: " . $e->getMessage());
            return 1;
        }
    }

    private function findProgramId($programName, $programs)
    {
        // Direct match
        if (isset($programs[$programName])) {
            return $programs[$programName]->id;
        }
        
        // Try to find by abbreviation or partial match
        $programName = strtoupper($programName);
        
        $mappings = [
            'BIT' => 'BSIT',
            'BACHELOR IN INDUSTRIAL TECHNOLOGY' => 'BSIT',
            'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY' => 'BSIT',
            'BTLED' => 'BTLEd',
            'BTVTED' => 'BTVTEd',
            'BACHELOR OF TECHNOLOGY AND LIVELIHOOD EDUCATION' => 'BTLEd',
            'BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION' => 'BTVTEd',
            'BSED' => 'BSEd',
            'BACHELOR OF SECONDARY EDUCATION' => 'BSEd',
            'BEED' => 'BEEd',
            'BACHELOR OF ELEMENTARY EDUCATION' => 'BEEd',
            'BSENTREP' => 'BSEntrep',
            'BACHELOR OF SCIENCE IN ENTREPRENEURSHIP' => 'BSEntrep',
        ];
        
        foreach ($mappings as $key => $value) {
            if (str_contains($programName, $key)) {
                foreach ($programs as $program) {
                    if (str_contains(strtoupper($program->name), $value)) {
                        return $program->id;
                    }
                }
            }
        }
        
        // Try fuzzy match
        foreach ($programs as $program) {
            if (str_contains($programName, strtoupper($program->name)) || 
                str_contains(strtoupper($program->name), $programName)) {
                return $program->id;
            }
        }
        
        return null;
    }
}
