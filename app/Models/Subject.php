<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'department_id',
        'description',
        'units',
        'program_id',
        'year_level',
        'semester',
        'is_active',
        'prerequisite_subject_ids',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'prerequisite_subject_ids' => 'array',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot('academic_year_id', 'status', 'grade', 'remarks')
            ->withTimestamps();
    }

    public function curricula(): BelongsToMany
    {
        return $this->belongsToMany(Curriculum::class, 'curriculum_subjects')
            ->withPivot('year_level', 'semester', 'is_required')
            ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
