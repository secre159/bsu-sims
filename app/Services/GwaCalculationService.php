<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\GradingScale;
use App\Models\Student;
use Illuminate\Support\Collection;

class GwaCalculationService
{
    /**
     * Calculate GWA (Grade Weighted Average) for a student
     * Uses Philippine grading scale (1.00-5.00)
     * Calculates cumulative GWA across all academic years by default,
     * or for a specific academic year if provided.
     */
    public function calculateStudentGwa(Student $student, ?AcademicYear $academicYear = null): array
    {
        // Build enrollment query
        $query = Enrollment::where('student_id', $student->id)
            ->whereNotNull('grade')
            ->with('subject');

        // If a specific academic year is provided, filter by it
        if ($academicYear) {
            $query->where('academic_year_id', $academicYear->id);
        }

        // Get all graded enrollments
        $enrollments = $query->get();

        if ($enrollments->isEmpty()) {
            return [
                'gwa' => null,
                'standing' => 'No Grades',
                'total_units' => 0,
                'graded_count' => 0,
            ];
        }

        // Calculate weighted GWA (Philippine scale: 1.00-5.00)
        $totalWeightedGrade = 0;
        $totalUnits = 0;
        $gradedCount = 0;

        foreach ($enrollments as $enrollment) {
            $grade = $enrollment->grade;
            
            // Skip null, IP, or INC grades
            if ($grade === null || in_array($grade, ['IP', 'INC'])) {
                continue;
            }

            $gradedCount++;
            $units = $enrollment->subject->units ?? 0;

            // Direct GWA calculation - no conversion needed
            if (is_numeric($grade)) {
                $totalWeightedGrade += (float)$grade * $units;
                $totalUnits += $units;
            }
        }

        $gwa = $totalUnits > 0 ? $totalWeightedGrade / $totalUnits : null;
        $gwa = $gwa !== null ? round($gwa, 2) : null;

        // Determine academic standing
        $standing = $this->determineAcademicStanding($gwa, $student);

        return [
            'gwa' => $gwa,
            'standing' => $standing,
            'total_units' => $totalUnits,
            'graded_count' => $gradedCount,
        ];
    }

    /**
     * Determine academic standing based on GWA (Philippine scale: 1.00-5.00)
     */
    private function determineAcademicStanding(?float $gwa, Student $student): string
    {
        if ($gwa === null) {
            return 'Unknown';
        }

        // Philippine grading scale standings
        if ($gwa <= 1.75) {
            return 'good';  // Excellent to Very Good
        } elseif ($gwa <= 2.50) {
            return 'good';  // Good to Satisfactory
        } elseif ($gwa <= 3.00) {
            return 'probation';  // Fair - needs improvement
        } else {
            return 'irregular';  // Poor performance (failed courses)
        }
    }

    /**
     * Update student academic standing based on calculated GWA
     */
    public function updateStudentStanding(Student $student, ?AcademicYear $academicYear = null): void
    {
        $calculation = $this->calculateStudentGwa($student, $academicYear);

        $student->update([
            'gwa' => $calculation['gwa'],
            'academic_standing' => $calculation['standing'],
            'is_irregular' => $calculation['gwa'] !== null && $calculation['gwa'] > 3.00, // Mark as irregular if GWA is above 3.00
        ]);
    }

    /**
     * Calculate GWA for multiple students (batch operation)
     */
    public function calculateBatchGwa(Collection $students, ?AcademicYear $academicYear = null): array
    {
        $results = [];

        foreach ($students as $student) {
            $calculation = $this->calculateStudentGwa($student, $academicYear);
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
