<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\GradingScale;
use App\Models\Student;
use Illuminate\Support\Collection;

class GpaCalculationService
{
    /**
     * Calculate GPA for a student in the current academic year
     */
    public function calculateStudentGpa(Student $student, ?AcademicYear $academicYear = null): array
    {
        if (!$academicYear) {
            $academicYear = AcademicYear::where('is_current', true)->first();
        }

        if (!$academicYear) {
            return [
                'gpa' => 0,
                'standing' => 'Unknown',
                'total_units' => 0,
                'graded_count' => 0,
            ];
        }

        // Get all graded enrollments for this student in this academic year
        $enrollments = Enrollment::where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)
            ->whereNotNull('grade')
            ->with('subject')
            ->get();

        if ($enrollments->isEmpty()) {
            return [
                'gpa' => 0,
                'standing' => 'No Grades',
                'total_units' => 0,
                'graded_count' => 0,
            ];
        }

        // Calculate weighted GPA
        $totalPoints = 0;
        $totalUnits = 0;
        $gradedCount = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->grade === null) {
                continue;
            }

            $gradedCount++;
            $gradePoints = $this->convertToGradePoints($enrollment->grade);
            $units = $enrollment->subject->units ?? 0;

            $totalPoints += $gradePoints * $units;
            $totalUnits += $units;
        }

        $gpa = $totalUnits > 0 ? $totalPoints / $totalUnits : 0;
        $gpa = round($gpa, 2);

        // Determine academic standing
        $standing = $this->determineAcademicStanding($gpa, $student);

        return [
            'gpa' => $gpa,
            'standing' => $standing,
            'total_units' => $totalUnits,
            'graded_count' => $gradedCount,
        ];
    }

    /**
     * Convert numerical grade to 4.0 scale grade points
     * Supports both Philippine scale (1.00-5.00) and percentage scale (0-100)
     */
    private function convertToGradePoints(float $grade): float
    {
        // Check if it's Philippine grading scale (1.00 to 5.00)
        if ($grade >= 1.00 && $grade <= 5.00) {
            // Philippine to 4.0 scale conversion
            if ($grade <= 1.25) return 4.0;  // Excellent
            if ($grade <= 1.75) return 3.5;  // Very Good
            if ($grade <= 2.25) return 3.0;  // Good
            if ($grade <= 2.75) return 2.5;  // Satisfactory
            if ($grade <= 3.00) return 2.0;  // Fair
            return 0.0; // Failed (5.00)
        }
        
        // Assume percentage scale (0-100)
        if ($grade >= 90) return 4.0;
        if ($grade >= 80) return 3.0;
        if ($grade >= 70) return 2.0;
        if ($grade >= 60) return 1.0;
        return 0.0;
    }

    /**
     * Determine academic standing based on GPA (4.0 scale)
     */
    private function determineAcademicStanding(float $gpa, Student $student): string
    {
        if ($gpa >= 3.5) {
            return 'good';  // Dean's Lister level
        } elseif ($gpa >= 2.0) {
            return 'good';  // Regular good standing
        } elseif ($gpa >= 1.5) {
            return 'probation';  // Academic probation
        } else {
            return 'irregular';  // Poor performance
        }
    }

    /**
     * Update student academic standing based on calculated GPA
     */
    public function updateStudentStanding(Student $student, ?AcademicYear $academicYear = null): void
    {
        $calculation = $this->calculateStudentGpa($student, $academicYear);

        $student->update([
            'gwa' => $calculation['gpa'],
            'academic_standing' => $calculation['standing'],
            'is_irregular' => $calculation['gpa'] < 2.0, // Mark as irregular if GPA is below 2.0
        ]);
    }

    /**
     * Calculate GPA for multiple students (batch operation)
     */
    public function calculateBatchGpa(Collection $students, ?AcademicYear $academicYear = null): array
    {
        $results = [];

        foreach ($students as $student) {
            $calculation = $this->calculateStudentGpa($student, $academicYear);
            $results[$student->id] = $calculation;

            $this->updateStudentStanding($student, $academicYear);
        }

        return $results;
    }

    /**
     * Get students affected by a grade batch
     */
    public function getAffectedStudents(array $enrollmentIds): Collection
    {
        return Enrollment::whereIn('id', $enrollmentIds)
            ->with('student')
            ->get()
            ->pluck('student')
            ->unique('id');
    }
}
