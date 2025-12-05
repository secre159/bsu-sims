<?php

namespace App\Services;

use App\Models\Backup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DatabaseBackupService
{
    protected $backupPath = 'backups';

    public function createBackup(?string $description = null): array
    {
        try {
            // Get database configuration
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            // Generate filename with timestamp
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $fullPath = storage_path('app/' . $this->backupPath . '/' . $filename);

            // Create backup directory if it doesn't exist
            if (!file_exists(storage_path('app/' . $this->backupPath))) {
                mkdir(storage_path('app/' . $this->backupPath), 0755, true);
            }

            // Build mysqldump command
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($database),
                escapeshellarg($fullPath)
            );

            // Execute backup
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('Backup command failed');
            }

            // Check if file was created
            if (!file_exists($fullPath)) {
                throw new \Exception('Backup file was not created');
            }

            $fileSize = filesize($fullPath);

            // Store backup record in database
            $backup = Backup::create([
                'filename' => $filename,
                'path' => $this->backupPath . '/' . $filename,
                'size' => $fileSize,
                'created_by' => Auth::id(),
                'description' => $description,
            ]);

            return [
                'success' => true,
                'backup' => $backup,
                'message' => 'Database backup created successfully',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create backup: ' . $e->getMessage(),
            ];
        }
    }

    public function restoreBackup(Backup $backup): array
    {
        try {
            $fullPath = storage_path('app/' . $backup->path);

            if (!file_exists($fullPath)) {
                throw new \Exception('Backup file not found');
            }

            // Get database configuration
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            // Build mysql restore command
            $command = sprintf(
                'mysql --user=%s --password=%s --host=%s --port=%s %s < %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($database),
                escapeshellarg($fullPath)
            );

            // Execute restore
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('Restore command failed');
            }

            return [
                'success' => true,
                'message' => 'Database restored successfully',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to restore backup: ' . $e->getMessage(),
            ];
        }
    }

    public function deleteBackup(Backup $backup): array
    {
        try {
            $fullPath = storage_path('app/' . $backup->path);

            // Delete file if exists
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            // Delete database record
            $backup->delete();

            return [
                'success' => true,
                'message' => 'Backup deleted successfully',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete backup: ' . $e->getMessage(),
            ];
        }
    }

    public function downloadBackup(Backup $backup)
    {
        $fullPath = storage_path('app/' . $backup->path);

        if (!file_exists($fullPath)) {
            return null;
        }

        return response()->download($fullPath, $backup->filename);
    }

    public function cleanOldBackups(int $daysToKeep = 30): array
    {
        try {
            $cutoffDate = now()->subDays($daysToKeep);
            
            $oldBackups = Backup::where('created_at', '<', $cutoffDate)->get();
            
            $deletedCount = 0;
            foreach ($oldBackups as $backup) {
                $result = $this->deleteBackup($backup);
                if ($result['success']) {
                    $deletedCount++;
                }
            }

            return [
                'success' => true,
                'deleted_count' => $deletedCount,
                'message' => "Cleaned $deletedCount old backup(s)",
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to clean old backups: ' . $e->getMessage(),
            ];
        }
    }
}
