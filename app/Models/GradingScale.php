<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradingScale extends Model
{
    protected $fillable = [
        'letter_grade',
        'min_score',
        'max_score',
        'grade_points',
        'description',
    ];

    protected $casts = [
        'min_score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'grade_points' => 'decimal:3,2',
    ];

    public function gradeHistories(): HasMany
    {
        return $this->hasMany(GradeHistory::class);
    }
}
