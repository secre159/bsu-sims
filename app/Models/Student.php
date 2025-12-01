<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'student_id', 'last_name', 'first_name', 'middle_name', 'suffix', 'maiden_name',
        'birthdate', 'date_of_birth', 'place_of_birth', 'gender', 'citizenship', 'ethnicity_tribal_affiliation',
        'contact_number', 'email', 'email_address', 'address', 'home_address', 'address_while_studying',
        'mother_name', 'mother_contact_number', 'father_contact_number',
        'emergency_contact_person', 'emergency_contact_relationship', 'emergency_contact_number',
        'program_id', 'year_level', 'status', 'photo_path', 'enrollment_date', 'academic_year_id',
        'student_type', 'degree', 'major', 'section', 'attendance_type', 'curriculum_used',
        'total_units_enrolled', 'free_higher_education_benefit', 'notes', 'gpa', 'academic_standing'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'enrollment_date' => 'date',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(StudentHistory::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'enrollments')
            ->withPivot('academic_year_id', 'status', 'grade', 'remarks')
            ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function academicStandingLogs(): HasMany
    {
        return $this->hasMany(AcademicStandingLog::class);
    }

    // Accessor for full_name
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim($this->last_name.', '.($this->first_name ?? '').' '.($this->middle_name ? substr($this->middle_name,0,1).'.' : '').($this->suffix ? ' '.$this->suffix : '')),
        );
    }

    /**
     * Check if student can register for courses
     * Only Active students can register
     */
    public function canRegister(): bool
    {
        return $this->status === 'Active';
    }

    /**
     * Check if student can graduate (placeholder logic)
     */
    public function canGraduate(): bool
    {
        return in_array($this->status, ['Active', 'Graduated']);
    }

}
