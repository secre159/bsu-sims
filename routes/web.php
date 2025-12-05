<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\StudentSubjectController;
use App\Http\Controllers\Student\StudentLoginController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentEnrollmentController;
use App\Http\Controllers\Chairperson\GradeController;
use App\Http\Controllers\Chairperson\GradeImportController;
use App\Http\Controllers\Chairperson\GradeBatchController;
use App\Http\Controllers\Chairperson\ChairpersonDashboardController;
use App\Http\Controllers\Chairperson\ChairpersonReportController;
use App\Http\Controllers\Admin\AdminGradeApprovalController;
use App\Http\Controllers\Admin\AdminGradeModificationController;
use App\Http\Controllers\Admin\GradeReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Landing page - show portal selection
Route::get('/', function () {
    return view('welcome');
});

// Student Portal Routes
Route::prefix('student')->name('student.')->group(function () {
    Route::post('/logout', [StudentLoginController::class, 'logout'])->name('logout');
    
    Route::middleware('auth:student')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [StudentProfileController::class, 'show'])->name('profile');
        Route::get('/enrollments', [StudentEnrollmentController::class, 'index'])->name('enrollments');
    });
});

// Protected routes - require authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - route to appropriate dashboard based on role
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'chairperson') {
            return redirect()->route('chairperson.dashboard');
        }
        return app(DashboardController::class)->index();
    })->name('dashboard');

    // Profile Management (all authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin-only routes
    Route::middleware('admin')->group(function () {
        // Students Management
        Route::resource('students', StudentController::class);
        Route::get('/students/{student}/history', [StudentController::class, 'history'])->name('students.history');
        Route::get('/students/{student}/id-card', [StudentController::class, 'generateIdCard'])->name('students.id-card');
        
        // Student Subject Enrollment
        Route::get('/students/{student}/subjects', [StudentSubjectController::class, 'index'])->name('students.subjects');
        Route::post('/students/{student}/subjects/enroll', [StudentSubjectController::class, 'enroll'])->name('students.subjects.enroll');
        Route::delete('/students/{student}/subjects/{enrollment}', [StudentSubjectController::class, 'drop'])->name('students.subjects.drop');
        
        // Student Import
        Route::get('/students-import', [ImportController::class, 'showImportForm'])->name('students.import.form');
        Route::post('/students-import', [ImportController::class, 'import'])->name('students.import');
        Route::get('/students-import/template', [ImportController::class, 'downloadTemplate'])->name('students.import.template');

        // Programs Management
        Route::resource('programs', ProgramController::class);

        // Subjects Management
        Route::resource('subjects', SubjectController::class);

        // Academic Years Management
        Route::resource('academic-years', AcademicYearController::class);
        Route::post('/academic-years/{academicYear}/set-current', [AcademicYearController::class, 'setCurrent'])->name('academic-years.set-current');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/students', [ReportController::class, 'studentsList'])->name('reports.students');
        Route::get('/reports/students/pdf', [ReportController::class, 'studentsListPdf'])->name('reports.students.pdf');
        Route::get('/reports/programs', [ReportController::class, 'programsList'])->name('reports.programs');
        Route::get('/reports/programs/pdf', [ReportController::class, 'programsListPdf'])->name('reports.programs.pdf');
        Route::get('/reports/year-levels', [ReportController::class, 'yearLevelsList'])->name('reports.year-levels');
        Route::get('/reports/year-levels/pdf', [ReportController::class, 'yearLevelsListPdf'])->name('reports.year-levels.pdf');
        Route::get('/reports/export-students', [ReportController::class, 'exportStudents'])->name('reports.export-students');

        // Activity Log
        Route::get('/activities', [ActivityLogController::class, 'index'])->name('activities.index');

        // User Management
        Route::resource('users', UserController::class);

        // Archive Management
        Route::get('/archive', [App\Http\Controllers\ArchiveController::class, 'index'])->name('archive.index');
        Route::get('/archive/create', [App\Http\Controllers\ArchiveController::class, 'create'])->name('archive.create');
        Route::post('/archive', [App\Http\Controllers\ArchiveController::class, 'store'])->name('archive.store');
        Route::get('/archive/{schoolYear}/{semester}', [App\Http\Controllers\ArchiveController::class, 'show'])->name('archive.show');
        Route::post('/archive/restore/{id}', [App\Http\Controllers\ArchiveController::class, 'restore'])->name('archive.restore');
        Route::delete('/archive/{schoolYear}/{semester}', [App\Http\Controllers\ArchiveController::class, 'destroy'])->name('archive.destroy');

        // Backup Management
        Route::get('/backups', [App\Http\Controllers\BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups', [App\Http\Controllers\BackupController::class, 'store'])->name('backups.store');
        Route::get('/backups/{backup}/download', [App\Http\Controllers\BackupController::class, 'download'])->name('backups.download');
        Route::post('/backups/{backup}/restore', [App\Http\Controllers\BackupController::class, 'restore'])->name('backups.restore');
        Route::delete('/backups/{backup}', [App\Http\Controllers\BackupController::class, 'destroy'])->name('backups.destroy');
        Route::post('/backups/cleanup', [App\Http\Controllers\BackupController::class, 'cleanup'])->name('backups.cleanup');

        // Semester Transition Management
        Route::prefix('semester-transition')->name('semester-transition.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SemesterTransitionController::class, 'index'])->name('index');
            Route::post('/validate', [App\Http\Controllers\Admin\SemesterTransitionController::class, 'validate'])->name('validate');
            Route::get('/confirm', [App\Http\Controllers\Admin\SemesterTransitionController::class, 'confirm'])->name('confirm');
            Route::post('/execute', [App\Http\Controllers\Admin\SemesterTransitionController::class, 'execute'])->name('execute');
        });
    });

    // Admin Grade Approval Routes (admin only)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::prefix('grade-approvals')->name('grade-approvals.')->group(function () {
            Route::get('/', [AdminGradeApprovalController::class, 'index'])->name('index');
            Route::get('/{batch}', [AdminGradeApprovalController::class, 'show'])->name('show');
            Route::post('/{batch}/approve', [AdminGradeApprovalController::class, 'approve'])->name('approve');
            Route::post('/{batch}/reject', [AdminGradeApprovalController::class, 'reject'])->name('reject');
        });

        // Grade Modification Routes
        Route::prefix('grade-modifications')->name('grade-modifications.')->group(function () {
            Route::get('/', [AdminGradeModificationController::class, 'index'])->name('index');
            Route::get('/{enrollment}/edit', [AdminGradeModificationController::class, 'edit'])->name('edit');
            Route::patch('/{enrollment}', [AdminGradeModificationController::class, 'update'])->name('update');
            Route::get('/{enrollment}/history', [AdminGradeModificationController::class, 'history'])->name('history');
        });

        // Grade Reports Routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [GradeReportController::class, 'index'])->name('index');
            Route::get('/gpa', [GradeReportController::class, 'gpaReport'])->name('gpa');
            Route::get('/irregular', [GradeReportController::class, 'irregularStudentsReport'])->name('irregular');
            Route::get('/deans-list', [GradeReportController::class, 'deansListReport'])->name('deans-list');
            Route::get('/distribution', [GradeReportController::class, 'gradeDistributionReport'])->name('distribution');
        });
    });

    // Chairperson Routes
    Route::middleware('chairperson')->prefix('chairperson')->name('chairperson.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [ChairpersonDashboardController::class, 'index'])->name('dashboard');
        
        // Grade Management
        Route::prefix('grades')->name('grades.')->group(function () {
            Route::get('/', [GradeController::class, 'index'])->name('index');
            
            // Student-centric view
            Route::get('/student/{student}', [GradeController::class, 'showStudent'])->name('student');
            
            // Subject-centric view with bulk entry
            Route::get('/subject/{subject}', [GradeController::class, 'showSubject'])->name('subject');
            Route::post('/subject/{subject}/bulk', [GradeController::class, 'bulkUpdate'])->name('bulk-update');
            
            // Individual enrollment grade entry
            Route::get('/{enrollment}/edit', [GradeController::class, 'edit'])->name('edit');
            Route::patch('/{enrollment}', [GradeController::class, 'update'])->name('update');
            Route::get('/{enrollment}/history', [GradeController::class, 'history'])->name('history');
        });

        // Excel Import
        Route::prefix('grade-import')->name('grade-import.')->group(function () {
            Route::get('/create', [GradeImportController::class, 'create'])->name('create');
            Route::get('/download-template/{subject}', [GradeImportController::class, 'downloadTemplate'])->name('download-template');
            Route::post('/', [GradeImportController::class, 'store'])->name('store');
            Route::post('/preview', [GradeImportController::class, 'preview'])->name('preview');
            Route::get('/preview/back', [GradeImportController::class, 'backToMapping'])->name('back-to-mapping');
            Route::post('/process', [GradeImportController::class, 'processImport'])->name('process');
            Route::post('/{batch}/submit', [GradeImportController::class, 'submit'])->name('submit');
        });

        // Grade Batch Management
        Route::prefix('grade-batches')->name('grade-batches.')->group(function () {
            Route::get('/', [GradeBatchController::class, 'index'])->name('index');
            Route::get('/{batch}', [GradeBatchController::class, 'show'])->name('show');
            Route::post('/{batch}/retry', [GradeBatchController::class, 'retry'])->name('retry');
            Route::delete('/{batch}', [GradeBatchController::class, 'destroy'])->name('destroy');
        });
        
        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ChairpersonReportController::class, 'index'])->name('index');
            Route::get('/students-list', [ChairpersonReportController::class, 'studentsList'])->name('students-list');
            Route::get('/grades-summary', [ChairpersonReportController::class, 'gradesSummary'])->name('grades-summary');
            Route::get('/irregular-students', [ChairpersonReportController::class, 'irregularStudents'])->name('irregular-students');
            Route::get('/export-students', [ChairpersonReportController::class, 'exportStudents'])->name('export-students');
            Route::get('/export-grades-summary', [ChairpersonReportController::class, 'exportGradesSummary'])->name('export-grades-summary');
        });
    });
});

require __DIR__.'/auth.php';
