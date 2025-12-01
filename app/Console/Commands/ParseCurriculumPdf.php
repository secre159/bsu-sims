<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Smalot\PdfParser\Parser;
use App\Models\Program;
use Illuminate\Support\Facades\Storage;

class ParseCurriculumPdf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'curriculum:parse {file_path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse curriculum PDF and extract course data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file_path');
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Parsing PDF: {$filePath}");
        
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();
            
            $courses = $this->extractCourses($text);
            
            $this->info("Found " . count($courses) . " courses");
            
            // Save to JSON for review before importing
            $outputPath = storage_path('app/curriculum_parsed.json');
            file_put_contents($outputPath, json_encode($courses, JSON_PRETTY_PRINT));
            
            $this->info("\nParsed data saved to: {$outputPath}");
            $this->info("\nReview the file, then run: php artisan curriculum:import");
            
            // Display sample
            $this->info("\nSample courses:");
            $this->table(
                ['Code', 'Name', 'Units (Lec/Lab)', 'Program', 'Year', 'Semester'],
                array_slice($courses, 0, 10)
            );
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Error parsing PDF: " . $e->getMessage());
            return 1;
        }
    }

    private function extractCourses($text)
    {
        $courses = [];
        $lines = explode("\n", $text);
        
        $currentProgram = null;
        $currentYearLevel = null;
        $semester = 2; // From PDF: Second Semester
        $academicYear = '2022-2023';
        
        for ($i = 0; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            
            // Detect program and year level
            if (preg_match('/Course & Year:\s*(.+?)\s+(\d)\s*(?:Major:|Section:)?/i', $line, $matches)) {
                $currentProgram = trim($matches[1]);
                $currentYearLevel = (int)$matches[2];
                $this->info("Found program: {$currentProgram} Year {$currentYearLevel}");
            }
            
            // Extract course data from table rows
            // Pattern: Course Code | Descriptive Title | Units (Lec/Lab)
            if (preg_match('/^([A-Z]+\s*\d+(?:\.\d+)?)\s+(.+?)\s+(\d)\s+(\d)\s*$/i', $line, $matches)) {
                $yearLevel = $currentYearLevel ?? 1;
                $yearLevelText = $this->formatYearLevel($yearLevel);
                $semesterText = $this->formatSemester($semester);
                
                $course = [
                    'code' => trim($matches[1]),
                    'name' => trim($matches[2]),
                    'units' => (int)$matches[3] + (int)$matches[4],
                    'program' => $currentProgram ?? 'Unknown',
                    'year_level' => $yearLevelText,
                    'semester' => $semesterText
                ];
                $courses[] = $course;
            }
        }
        
        // Remove duplicates
        $unique = [];
        foreach ($courses as $course) {
            $key = $course['code'] . '_' . $course['program'];
            if (!isset($unique[$key])) {
                $unique[$key] = $course;
            }
        }
        
        return array_values($unique);
    }

    private function formatYearLevel($year)
    {
        $ordinals = [
            1 => '1st Year',
            2 => '2nd Year',
            3 => '3rd Year',
            4 => '4th Year',
            5 => '5th Year',
        ];
        return $ordinals[$year] ?? '1st Year';
    }

    private function formatSemester($semester)
    {
        $semesters = [
            1 => '1st Semester',
            2 => '2nd Semester',
            3 => 'Summer',
        ];
        return $semesters[$semester] ?? '2nd Semester';
    }
}
