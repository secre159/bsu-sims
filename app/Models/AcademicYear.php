<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    protected $fillable = [
        'year_code', 'semester', 'start_date', 'end_date', 'is_current',
        'registration_start_date', 'registration_end_date', 'add_drop_deadline',
        'classes_start_date', 'classes_end_date',
        'midterm_start_date', 'midterm_end_date',
        'exam_start_date', 'exam_end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'registration_start_date' => 'date',
        'registration_end_date' => 'date',
        'add_drop_deadline' => 'date',
        'classes_start_date' => 'date',
        'classes_end_date' => 'date',
        'midterm_start_date' => 'date',
        'midterm_end_date' => 'date',
        'exam_start_date' => 'date',
        'exam_end_date' => 'date',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
