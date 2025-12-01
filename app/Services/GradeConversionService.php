<?php

namespace App\Services;

use App\Models\GradingScale;

class GradeConversionService
{
    /**
     * Convert raw score (0-100) to Philippine letter grade (1.00-5.00)
     * 
     * Scale:
     * 96-100 = 1.00 (Excellent)
     * 93-95  = 1.25 (Excellent)
     * 90-92  = 1.50 (Very Good)
     * 87-89  = 1.75 (Very Good)
     * 84-86  = 2.00 (Good)
     * 81-83  = 2.25 (Good)
     * 78-80  = 2.50 (Satisfactory)
     * 75-77  = 2.75 (Satisfactory)
     * 74     = 3.00 (Passing)
     * 0-73   = 5.00 (Failed)
     */
    public static function rawToLetterGrade(float $rawScore): string
    {
        if ($rawScore >= 96) return '1.00';
        if ($rawScore >= 93) return '1.25';
        if ($rawScore >= 90) return '1.50';
        if ($rawScore >= 87) return '1.75';
        if ($rawScore >= 84) return '2.00';
        if ($rawScore >= 81) return '2.25';
        if ($rawScore >= 78) return '2.50';
        if ($rawScore >= 75) return '2.75';
        if ($rawScore >= 74) return '3.00';
        return '5.00';
    }

    /**
     * Convert raw score to grade points (same as letter grade in Philippine system)
     */
    public static function rawToGradePoints(float $rawScore): float
    {
        return (float) self::rawToLetterGrade($rawScore);
    }

    /**
     * Check if a grade is passing (3.00 or better, lower is better)
     */
    public static function isPassing(float $grade): bool
    {
        // In Philippine system, 3.00 is passing, 5.00 is failing
        // Lower grade = better (1.00 is best)
        return $grade <= 3.00;
    }

    /**
     * Get grade description
     */
    public static function getDescription(float $grade): string
    {
        if ($grade <= 1.25) return 'Excellent';
        if ($grade <= 1.75) return 'Very Good';
        if ($grade <= 2.25) return 'Good';
        if ($grade <= 2.75) return 'Satisfactory';
        if ($grade <= 3.00) return 'Passing';
        return 'Failed';
    }

    /**
     * Get academic standing based on GWA (General Weighted Average)
     * Lower GWA = Better (Philippine system)
     */
    public static function getAcademicStanding(float $gwa): string
    {
        if ($gwa <= 1.25) return "Dean's Lister";
        if ($gwa <= 1.75) return "With Honors";
        if ($gwa <= 2.00) return "Active";
        if ($gwa <= 3.00) return "Regular";
        return "Irregular/Probation";
    }

    /**
     * Calculate GWA (General Weighted Average) from enrollments
     * In Philippine system: GWA = Σ(grade × units) / Σ(units)
     */
    public static function calculateGWA(iterable $enrollments): ?float
    {
        $totalPoints = 0;
        $totalUnits = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->grade === null) continue;
            
            $units = $enrollment->subject->units ?? 3;
            $gradePoints = (float) $enrollment->grade;
            
            $totalPoints += $gradePoints * $units;
            $totalUnits += $units;
        }

        if ($totalUnits === 0) return null;

        return round($totalPoints / $totalUnits, 2);
    }

    /**
     * Format grade for display
     */
    public static function formatGrade(?float $grade): string
    {
        if ($grade === null) return '—';
        return number_format($grade, 2);
    }

    /**
     * Get color class for grade (for UI display)
     */
    public static function getGradeColorClass(float $grade): string
    {
        if ($grade <= 1.50) return 'text-green-600'; // Excellent/Very Good
        if ($grade <= 2.25) return 'text-blue-600';  // Good
        if ($grade <= 3.00) return 'text-yellow-600'; // Satisfactory/Passing
        return 'text-red-600'; // Failed
    }
}
