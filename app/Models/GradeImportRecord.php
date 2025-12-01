<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeImportRecord extends Model
{
    protected $fillable = [
        'grade_import_batch_id',
        'enrollment_id',
        'student_id_number',
        'subject_code',
        'grade',
        'status',
        'error_message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function gradeImportBatch(): BelongsTo
    {
        return $this->belongsTo(GradeImportBatch::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
}
