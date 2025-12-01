<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchivedStudent extends Model
{
    protected $fillable = [
        'student_data',
        'student_id',
        'name',
        'program_id',
        'year_level',
        'status',
        'archived_school_year',
        'archived_semester',
        'archived_at',
        'archived_by',
        'archive_reason',
    ];

    protected $casts = [
        'student_data' => 'array',
        'archived_at' => 'datetime',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function archivedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }
}
