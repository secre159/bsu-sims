<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'academic_year_id',
        'status',
        'grade',
        'remarks',
        'submission_date',
        'approver_id',
        'approved_at',
    ];

    protected $casts = [
        'submission_date' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function gradeHistories(): HasMany
    {
        return $this->hasMany(GradeHistory::class);
    }

    public function gradeImportRecords(): HasMany
    {
        return $this->hasMany(GradeImportRecord::class);
    }
}
