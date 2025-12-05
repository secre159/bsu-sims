<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use App\Services\DatabaseBackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(DatabaseBackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    public function index()
    {
        $backups = Backup::with('createdBy')
            ->latest()
            ->paginate(15);

        $totalSize = Backup::sum('size');

        return view('backups.index', compact('backups', 'totalSize'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string|max:500',
        ]);

        $result = $this->backupService->createBackup($request->description);

        if ($result['success']) {
            return redirect()->route('backups.index')
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function download(Backup $backup)
    {
        $response = $this->backupService->downloadBackup($backup);

        if (!$response) {
            return back()->with('error', 'Backup file not found');
        }

        return $response;
    }

    public function restore(Backup $backup)
    {
        $result = $this->backupService->restoreBackup($backup);

        if ($result['success']) {
            return redirect()->route('backups.index')
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function destroy(Backup $backup)
    {
        $result = $this->backupService->deleteBackup($backup);

        if ($result['success']) {
            return redirect()->route('backups.index')
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function cleanup()
    {
        $result = $this->backupService->cleanOldBackups(30);

        if ($result['success']) {
            return redirect()->route('backups.index')
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
