<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Support\Collection;

class SemesterTransitionService
{
    private AcademicStandingService $standingService;
    
    private array $yearLevelMap = [
        '1st Year' => 1,
        '2nd Year' => 2,
        '3rd Year' => 3,
        '4th Year' => 4,
    ];
    
    private array $yearLevelReverseMap = [
        1 => '1st Year',
        2 => '2nd Year',
        3 => '3rd Year',
        4 => '4th Year',
    ];

    public function __construct(AcademicStandingService $standingService)
    {
        $this->standingService = $standingService;
    }
    
    /**
     * Convert year level enum to numeric value
     */
    private function yearLevelToNumber(string $yearLevel): int
    {
        return $this->yearLevelMap[$yearLevel] ?? 1;
    }
    
    /**
     * Convert numeric year level to enum string
     */
    private function numberToYearLevel(int $level): string
    {
        return $this->yearLevelReverseMap[$level] ?? '1st Year';
    }

    /**
     * Prepare year transition - validate prerequisites
     */
    public function prepareYearTransition(AcademicYear $currentYear): array
    {
        // Only get students with enrollments in the current year
        $students = Student::whereHas('enrollments', function ($query) use ($currentYear) {
            $query->where('academic_year_id', $currentYear->id);
        })->get();
        $validation = [
            'ready' => true,
            'warnings' => [],
            'errors' => [],
            'statistics' => [
                'total_students' => 0,
                'promoted_normal' => 0,
                'promoted_irregular' => 0,
                'retained' => 0,
                'probation' => 0,
            ],
        ];

        foreach ($students as $student) {
            $validation['statistics']['total_students']++;
            
            // Check for pending grade batches
            $pendingBatches = $student->gradeImportBatches()
                ->whereIn('status', ['processing', 'pending', 'submitted'])
                ->count();

            if ($pendingBatches > 0) {
                $validation['errors'][] = "Student {$student->student_id} has pending grade batches";
                $validation['ready'] = false;
            }

            // Calculate standing
            $standing = $this->standingService->determineStanding($student, $currentYear);
            $progression = $standing['progression'];

            match ($progression) {
                'promoted_normal' => $validation['statistics']['promoted_normal']++,
                'promoted_irregular' => $validation['statistics']['promoted_irregular']++,
                'retained' => $validation['statistics']['retained']++,
                'probation' => $validation['statistics']['probation']++,
                default => null,
            };

            // Check for incomplete grades
            $incompleteCount = $student->enrollments()
                ->where('academic_year_id', $currentYear->id)
                ->where('grade', 'INC')
                ->count();

            if ($incompleteCount > 0) {
                $validation['warnings'][] = "Student {$student->student_id} has {$incompleteCount} incomplete grades";
            }
        }

        return $validation;
    }

    /**
     * Execute year transition
     */
    public function executeYearTransition(AcademicYear $currentYear, AcademicYear $nextYear): array
    {
        $results = [
            'success' => true,
            'message' => '',
            'students_processed' => 0,
            'enrollments_created' => 0,
            'errors' => [],
        ];

        // Only get students with enrollments in the current year
        $students = Student::whereHas('enrollments', function ($query) use ($currentYear) {
            $query->where('academic_year_id', $currentYear->id);
        })->get();

        foreach ($students as $student) {
            try {
                $this->transitionStudent($student, $currentYear, $nextYear);
                $results['students_processed']++;
            } catch (\Exception $e) {
                $results['errors'][] = "Error transitioning student {$student->student_id}: {$e->getMessage()}";
                $results['success'] = false;
            }
        }

        $results['message'] = "Processed {$results['students_processed']} students. Created new enrollments for next academic year.";

        return $results;
    }

    /**
     * Transition individual student
     */
    public function transitionStudent(Student $student, AcademicYear $currentYear, AcademicYear $nextYear): void
    {
        // Update student academic standing
        $standingData = $this->standingService->updateStudentStanding($student, $currentYear);
        $progression = $standingData['progression'];

        // Get student's current enrollments
        $currentEnrollments = $student->enrollments()
            ->where('academic_year_id', $currentYear->id)
            ->with('subject')
            ->get();

        // Create new enrollments based on progression
        match ($progression) {
            'promoted_normal' => $this->createNormalProgressionEnrollments($student, $currentEnrollments, $nextYear),
            'promoted_irregular' => $this->createIrregularProgressionEnrollments($student, $currentEnrollments, $nextYear, $currentYear),
            'retained' => $this->createRetainedEnrollments($student, $currentEnrollments, $nextYear),
            'probation' => null, // No auto-enrollment for probation students
            default => null,
        };
    }

    /**
     * Create enrollments for normally promoted students (no failures)
     */
    private function createNormalProgressionEnrollments(Student $student, Collection $currentEnrollments, AcademicYear $nextYear): void
    {
        // Get subjects for next year level (advance year level)
        $currentLevel = $this->yearLevelToNumber($student->year_level);
        $nextLevel = $currentLevel + 1;
        
        // Check if next level has subjects. If not, student graduates
        $nextYearLevelEnum = $this->numberToYearLevel($nextLevel);
        $nextLevelSubjects = $student->program->subjects()
            ->where('year_level', $nextYearLevelEnum)
            ->get();

        if ($nextLevelSubjects->isEmpty()) {
            // No subjects for next level - student graduates
            $student->update([
                'year_level' => $student->year_level, // Keep current level
                'status' => 'Graduated',
            ]);
            return;
        }

        // Enroll in subjects for next level
        foreach ($nextLevelSubjects as $subject) {
            // Check if not already enrolled
            $exists = $student->enrollments()
                ->where('subject_id', $subject->id)
                ->where('academic_year_id', $nextYear->id)
                ->exists();

            if (!$exists) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $nextYear->id,
                    'status' => 'Enrolled',
                    'enrollment_type' => 'regular',
                ]);
            }
        }

        // Update student year level
        $student->update(['year_level' => $nextYearLevelEnum]);
    }

    /**
     * Create enrollments for promoted irregular students (1-2 failures)
     */
    private function createIrregularProgressionEnrollments(Student $student, Collection $currentEnrollments, AcademicYear $nextYear, AcademicYear $currentYear): void
    {
        // Advance year level
        $currentLevel = $this->yearLevelToNumber($student->year_level);
        $nextLevel = $currentLevel + 1;
        
        $nextYearLevelEnum = $this->numberToYearLevel($nextLevel);

        // Enroll in next level subjects
        $nextLevelSubjects = $student->program->subjects()
            ->where('year_level', $nextYearLevelEnum)
            ->get();
        
        // Check if this is the last year
        $isLastYear = $nextLevelSubjects->isEmpty();

        if (!$isLastYear) {
            foreach ($nextLevelSubjects as $subject) {
                $exists = $student->enrollments()
                    ->where('subject_id', $subject->id)
                    ->where('academic_year_id', $nextYear->id)
                    ->exists();

                if (!$exists) {
                    Enrollment::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'academic_year_id' => $nextYear->id,
                        'status' => 'Enrolled',
                        'enrollment_type' => 'regular',
                    ]);
                }
            }
        }

        // Enroll in failed subjects for retake (even if graduating, they can retake)
        $failedSubjects = $this->standingService->getFailedSubjects($student, $currentYear);

        foreach ($failedSubjects as $failedEnrollment) {
            $subject = $failedEnrollment['subject'];

            Enrollment::create([
                'student_id' => $student->id,
                'subject_id' => $subject['id'],
                'academic_year_id' => $nextYear->id,
                'status' => 'Enrolled',
                'enrollment_type' => 'retake',
            ]);
        }

        // Update student year level
        if ($isLastYear) {
            // Graduating - keep current year level
            $student->update(['year_level' => $student->year_level]);
        } else {
            $student->update(['year_level' => $nextYearLevelEnum]);
        }
    }

    /**
     * Create enrollments for retained students (3+ failures)
     */
    private function createRetainedEnrollments(Student $student, Collection $currentEnrollments, AcademicYear $nextYear): void
    {
        // Re-enroll in same year level subjects
        $currentYearLevelSubjects = $student->program->subjects()
            ->where('year_level', $student->year_level)
            ->get();

        foreach ($currentYearLevelSubjects as $subject) {
            $exists = $student->enrollments()
                ->where('subject_id', $subject->id)
                ->where('academic_year_id', $nextYear->id)
                ->exists();

            if (!$exists) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $nextYear->id,
                    'status' => 'Enrolled',
                    'enrollment_type' => 'regular',
                ]);
            }
        }

        // Do not advance year level
    }
}
