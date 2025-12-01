<?php

namespace App\Services;

class GradeNormalizer
{
    /**
     * Valid Philippine grades
     */
    private array $validPhilippineGrades = ['1.00', '1.25', '1.50', '1.75', '2.00', '2.25', '2.50', '2.75', '3.00', '5.00'];

    /**
     * Special grades
     */
    private array $specialGrades = ['IP', 'INC'];

    /**
     * Normalize and validate a grade value
     * Accepts: numeric 0-100, Philippine scale grades, or special grades (IP/INC)
     * Returns: normalized grade string or error array
     */
    public function normalize(mixed $grade): array
    {
        if (empty($grade)) {
            return ['valid' => false, 'error' => 'Grade is required', 'value' => null];
        }

        $gradeStr = (string)trim($grade);

        // Check for special grades
        if (in_array(strtoupper($gradeStr), $this->specialGrades)) {
            return ['valid' => true, 'value' => strtoupper($gradeStr)];
        }

        // Check if it's a valid Philippine scale grade
        if (in_array($gradeStr, $this->validPhilippineGrades)) {
            return ['valid' => true, 'value' => $gradeStr];
        }

        // Try to convert from percentage (0-100) to Philippine scale
        if (is_numeric($gradeStr)) {
            $numericGrade = (float)$gradeStr;

            // Validate range
            if ($numericGrade < 0 || $numericGrade > 100) {
                return ['valid' => false, 'error' => 'Grade must be between 0 and 100', 'value' => null];
            }

            // Convert to Philippine scale
            $converted = $this->convertFromPercentage($numericGrade);
            if ($converted) {
                return ['valid' => true, 'value' => $converted];
            }
        }

        return ['valid' => false, 'error' => 'Grade must be a number (0-100), Philippine scale (1.00-3.00, 5.00), or special grade (IP, INC)', 'value' => null];
    }

    /**
     * Convert percentage (0-100) to Philippine grading scale
     * 96-100 = 1.00
     * 93-95 = 1.25
     * 90-92 = 1.50
     * 87-89 = 1.75
     * 84-86 = 2.00
     * 81-83 = 2.25
     * 78-80 = 2.50
     * 75-77 = 2.75
     * 74 = 3.00
     * Below 74 = 5.00
     */
    private function convertFromPercentage(float $percentage): ?string
    {
        if ($percentage >= 96) return '1.00';
        if ($percentage >= 93) return '1.25';
        if ($percentage >= 90) return '1.50';
        if ($percentage >= 87) return '1.75';
        if ($percentage >= 84) return '2.00';
        if ($percentage >= 81) return '2.25';
        if ($percentage >= 78) return '2.50';
        if ($percentage >= 75) return '2.75';
        if ($percentage >= 74) return '3.00';
        return '5.00';
    }

    /**
     * Get description of a grade
     */
    public function getGradeDescription(string $grade): string
    {
        return match ($grade) {
            '1.00' => 'Excellent (96-100)',
            '1.25' => 'Excellent (93-95)',
            '1.50' => 'Very Good (90-92)',
            '1.75' => 'Very Good (87-89)',
            '2.00' => 'Good (84-86)',
            '2.25' => 'Good (81-83)',
            '2.50' => 'Satisfactory (78-80)',
            '2.75' => 'Satisfactory (75-77)',
            '3.00' => 'Passing (74)',
            '5.00' => 'Failed (Below 74)',
            'IP' => 'In Progress',
            'INC' => 'Incomplete',
            default => 'Unknown',
        };
    }
}
