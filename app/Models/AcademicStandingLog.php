<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicStandingLog extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'from_standing',
        'to_standing',
        'gwa_calculated',
        'failed_subjects_count',
        'reason',
    ];

    protected $casts = [
        'gwa_calculated' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
