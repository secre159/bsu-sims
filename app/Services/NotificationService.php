<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Notify chairperson when batch is uploaded
     */
    public function notifyBatchUploaded(User $chairperson, string $fileName, int $recordCount): void
    {
        Notification::create([
            'user_id' => $chairperson->id,
            'type' => 'grade_batch_uploaded',
            'title' => 'Grade Batch Uploaded',
            'message' => "Your grade file '{$fileName}' with {$recordCount} records has been uploaded and is pending approval.",
            'action_url' => route('chairperson.grade-batches.index'),
        ]);
    }

    /**
     * Notify chairperson when batch is approved
     */
    public function notifyBatchApproved(User $chairperson, string $fileName, int $appliedCount): void
    {
        Notification::create([
            'user_id' => $chairperson->id,
            'type' => 'grade_batch_approved',
            'title' => 'Grade Batch Approved',
            'message' => "Your grade batch '{$fileName}' has been approved. {$appliedCount} grades have been applied.",
            'action_url' => route('chairperson.grade-batches.index'),
        ]);
    }

    /**
     * Notify chairperson when batch is rejected
     */
    public function notifyBatchRejected(User $chairperson, string $fileName, string $reason = ''): void
    {
        $message = "Your grade batch '{$fileName}' has been rejected.";
        if (!empty($reason)) {
            $message .= " Reason: {$reason}";
        }

        Notification::create([
            'user_id' => $chairperson->id,
            'type' => 'grade_batch_rejected',
            'title' => 'Grade Batch Rejected',
            'message' => $message,
            'action_url' => route('chairperson.grade-batches.index'),
        ]);
    }

    /**
     * Notify admin about irregular students
     */
    public function notifyIrregularStudents(User $admin, int $count): void
    {
        Notification::create([
            'user_id' => $admin->id,
            'type' => 'irregular_students',
            'title' => 'Irregular Students Report',
            'message' => "{$count} student(s) have been marked as irregular due to low GPA.",
            'action_url' => route('admin.grade-modifications.index') . '?standing=Irregular',
        ]);
    }

    /**
     * Notify admin about promotion completion
     */
    public function notifyPromotionComplete(User $admin, int $promoted, int $irregular): void
    {
        Notification::create([
            'user_id' => $admin->id,
            'type' => 'promotion_complete',
            'title' => 'Student Promotion Complete',
            'message' => "{$promoted} students promoted to next year. {$irregular} marked as irregular.",
            'action_url' => route('archive.index'),
        ]);
    }

    /**
     * Get unread notifications for user
     */
    public function getUnreadNotifications(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return $user->notifications()
            ->where('read', false)
            ->latest()
            ->get();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead(User $user): void
    {
        $user->notifications()
            ->where('read', false)
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);
    }
}
