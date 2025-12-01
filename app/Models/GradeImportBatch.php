<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeImportBatch extends Model
{
    protected $fillable = [
        'chairperson_id',
        'file_name',
        'total_records',
        'success_count',
        'error_count',
        'status',
        'submitted_at',
        'column_mapping',
    ];

    protected $casts = [
        'column_mapping' => 'array',
        'submitted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function chairperson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chairperson_id');
    }

    public function gradeImportRecords(): HasMany
    {
        return $this->hasMany(GradeImportRecord::class);
    }
}
