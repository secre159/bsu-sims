<?php

namespace App\Services;

use App\Models\Student;
use App\Models\AcademicYear;

class AcademicStandingService
{
    /**
     * Grade to GPA conversion table (Philippine scale)
     */
    private array $gradeWeights = [
        '1.00' => 4.0,
        '1.25' => 3.75,
        '1.50' => 3.5,
        '1.75' => 3.25,
        '2.00' => 3.0,
        '2.25' => 2.75,
        '2.50' => 2.5,
        '2.75' => 2.25,
        '3.00' => 2.0,
        '5.00' => 0.0,
        'IP' => null,
        'INC' => null,
    ];

    /**
     * Calculate GWA (Grade Weighted Average) for a student
     */
    public function calculateGWA(Student $student, AcademicYear $academicYear): ?float
    {
        $enrollments = $student->enrollments()
            ->where('academic_year_id', $academicYear->id)
            ->whereNotNull('grade')
            ->get();

        if ($enrollments->isEmpty()) {
            return null;
        }

        $totalWeight = 0;
        $totalCredits = 0;

        foreach ($enrollments as $enrollment) {
            $grade = $enrollment->grade;
            
            // Skip incomplete or in-progress grades
            if (in_array($grade, ['IP', 'INC'])) {
                continue;
            }

            // Get grade weight
            $weight = $this->gradeWeights[$grade] ?? null;
            if ($weight === null) {
                continue;
            }

            // Get subject credit units (assuming subject has units field)
            $units = $enrollment->subject->units ?? 1;

            $totalWeight += $weight * $units;
            $totalCredits += $units;
        }

        if ($totalCredits === 0) {
            return null;
        }

        return round($totalWeight / $totalCredits, 2);
    }

    /**
     * Get failed subjects for a student
     */
    public function getFailedSubjects(Student $student, AcademicYear $academicYear): array
    {
        return $student->enrollments()
            ->where('academic_year_id', $academicYear->id)
            ->where('grade', '5.00')
            ->with('subject')
            ->get()
            ->toArray();
    }

    /**
     * Count failed subjects for a student
     */
    public function getFailedSubjectsCount(Student $student, AcademicYear $academicYear): int
    {
        return $student->enrollments()
            ->where('academic_year_id', $academicYear->id)
            ->where('grade', '5.00')
            ->count();
    }

    /**
     * Determine academic standing and progression
     * Returns: 'promoted_normal', 'promoted_irregular', 'retained', 'probation'
     */
    public function determineStanding(Student $student, AcademicYear $academicYear): array
    {
        $gwa = $this->calculateGWA($student, $academicYear);
        $failedCount = $this->getFailedSubjectsCount($student, $academicYear);

        // Determine standing based on failures and GWA
        if ($failedCount === 0) {
            $standing = 'good';
            $progression = 'promoted_normal';
        } elseif ($failedCount >= 1 && $failedCount <= 2) {
            $standing = 'irregular';
            $progression = 'promoted_irregular';
        } elseif ($failedCount >= 3) {
            $standing = 'retained';
            $progression = 'retained';
        } elseif ($gwa !== null && $gwa < 1.0) {
            $standing = 'probation';
            $progression = 'probation';
        } else {
            $standing = 'good';
            $progression = 'promoted_normal';
        }

        return [
            'standing' => $standing,
            'progression' => $progression,
            'gwa' => $gwa,
            'failed_count' => $failedCount,
        ];
    }

    /**
     * Update student's academic standing
     */
    public function updateStudentStanding(Student $student, AcademicYear $academicYear): array
    {
        $standingData = $this->determineStanding($student, $academicYear);

        // Update student record
        $oldStanding = $student->academic_standing;
        $student->update([
            'academic_standing' => $standingData['standing'],
            'gwa' => $standingData['gwa'],
            'is_irregular' => $standingData['standing'] === 'irregular',
        ]);

        // Log the change
        if ($oldStanding !== $standingData['standing']) {
            $student->academicStandingLogs()->create([
                'academic_year_id' => $academicYear->id,
                'from_standing' => $oldStanding,
                'to_standing' => $standingData['standing'],
                'gwa_calculated' => $standingData['gwa'],
                'failed_subjects_count' => $standingData['failed_count'],
                'reason' => 'Year-end standing calculation',
            ]);
        }

        return $standingData;
    }
}
