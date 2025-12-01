<?php

namespace App\Traits;

use App\Models\Activity;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity($model, 'created', 'Created ' . class_basename($model));
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']);
            
            if (!empty($changes)) {
                static::logActivity($model, 'updated', 'Updated ' . class_basename($model), [
                    'old' => $model->getOriginal(),
                    'new' => $changes,
                ]);
            }
        });

        static::deleted(function ($model) {
            static::logActivity($model, 'deleted', 'Deleted ' . class_basename($model));
        });
    }

    protected static function logActivity($model, $action, $description, $properties = [])
    {
        if (!auth()->check()) {
            return;
        }

        Activity::create([
            'user_id' => auth()->id(),
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'action' => $action,
            'description' => $description,
            'properties' => $properties,
        ]);
    }
}
