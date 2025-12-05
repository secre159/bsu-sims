# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Key Commands

### Setup & Environment
- Install PHP dependencies: `composer install`
- Install Node dependencies: `npm install`
- Create environment file and key (first run):
  - `cp .env.example .env`
  - `php artisan key:generate`
- Run database migrations and seed full demo data:
  - Initial migrate & seed: `php artisan migrate --seed`
  - Full reset (rebuild schema + seed test scenarios): `php artisan migrate:fresh --seed`
- Create storage symlink for uploads: `php artisan storage:link`

### Running the Application
- Simple dev server (backend only): `php artisan serve`
- Frontend dev (Vite): `npm run dev`
- Production asset build: `npm run build`
- Full dev stack via Composer (PHP server, queue listener, log viewer, Vite in one command):
  - `composer dev`

### Database Utilities
- Reseed only specific data (see seeders for details): `php artisan db:seed --class=SampleActivitiesSeeder`
- Recalculate GWA for all students with grades: `php artisan gwa:recalculate`
  - For a specific student: `php artisan gwa:recalculate --student_id=2024-0001`
- Clear optimized caches when changing config/routes/views:
  - `php artisan optimize:clear`
  - `php artisan route:clear`
  - `php artisan view:clear`

### Testing
- Run the full Laravel test suite: `php artisan test`
- Run tests via Composer script (also clears config cache): `composer test`
- Run a single test case or method (replace placeholders with real names):
  - By class name: `php artisan test --filter=SomeTestClassName`
  - By file: `php artisan test tests/Feature/SomeFeatureTest.php`

### Code Style (PHP)
- Laravel Pint is installed as a dev dependency; run it from project root:
  - `./vendor/bin/pint`

### Data & Scenario Helpers
- Start server and log in with seeded admin (from docs):
  - `php artisan serve`
  - Email: `admin@bsu-bokod.edu.ph`
- Query rich test scenarios in Tinker (see `QUICK_REFERENCE.md` for full details):
  - `php artisan tinker`

## High-Level Architecture

This is a Laravel-based Student Information Management System for BSU-Bokod. The core design revolves around clear separation of concerns between roles (Admin, Chairperson, Student), strong audit logging, and services for academic rules (GPA, standing, promotion, grade import).

### Core Domains & Models

- **Student & Enrollment domain**
  - `Student` holds the canonical profile (program, year level, status, contact info, academic standing, GWA).
  - `Program` defines academic programs and is the parent for both students and curriculum subjects.
  - `Subject` (see `app/Models/Subject.php`) defines course offerings (code, year level, units, active flag) and drives enrollment and prerequisite rules.
  - `Enrollment` links students to subjects within an `AcademicYear`, with fields like `status` (Enrolled/Completed/Failed/Dropped), `grade`, and `enrollment_type` (regular/retake).

- **Academic calendar & progression**
  - `AcademicYear` models year/semester and all key dates (registration window, add/drop, class schedule, midterms, exams) plus an `is_current` flag.
  - `Student` references an `AcademicYear` where relevant (e.g., initial enrollment date/year), and progression logic depends on completed enrollments per academic year.

- **Grading & standing**
  - `GradingScale` (and related grade conversion helpers) store grading rules.
  - `GradeImportBatch` and `GradeImportRecord` capture uploads from chairpersons before they are approved and applied to `Enrollment` records.
  - `Notification` stores in-app notifications to admins/chairpersons about grade workflow and promotion.
  - `Activity` is the audit trail for all important user actions (creation, updates, drops, promotions, etc.).

- **Archiving**
  - `ArchivedStudent` stores a snapshot of `Student` data plus some denormalized fields (school year, semester, status, program) to support historical reporting and restoration.

Most of these are wired via Eloquent relationships and are surfaced in controllers described below.

### Route-Level Structure & Roles

All routes are defined in `routes/web.php` and grouped by guard/role:

- **Root redirect**
  - `/` redirects to the authenticated dashboard (`dashboard` route).

- **Student Portal (`prefix: student`, guard: `auth:student`)**
  - Controllers under `App\Http\Controllers\Student\*`:
    - `StudentLoginController`: handles student login/logout (POST `/student/logout`).
    - `StudentDashboardController`: `/student/dashboard` — student-facing overview (current program, year level, enrolled subjects, etc.).
    - `StudentProfileController`: `/student/profile` — readonly profile view for a student.
    - `StudentEnrollmentController`: `/student/enrollments` — view of enrollments and their statuses.
  - This module is read-heavy and exposes only the student’s own data; changes (enrollment, grades) flow through admin/chairperson modules.

- **Authenticated Staff (`middleware: auth, verified`)**
  - **Dashboard**
    - `DashboardController@index` (`/dashboard`): aggregates counts and distributions (total/active/graduated students, students per program, per year level, per status) and surfaces recent `Activity` records for at-a-glance status.
  - **Profile**
    - `ProfileController` handles user account edit/update/delete.

- **Admin-only block (`middleware: admin`)**

  - **Student Management**
    - `StudentController` (resource `students`): full CRUD plus extensions:
      - Validates rich student profiles with consistency checks between `year_level` and `status` (e.g., cannot mark 1st/2nd year students as Graduated).
      - Manages profile photos in `storage/app/public/photos` with safe replacement and cleanup.
      - Emits detailed `Activity` records on create/update (including per-field change logs in `properties[changes]`).
      - `history(Student)` shows a timeline of `Student` history-related events.
      - `generateIdCard(Student)` renders a PDF ID card via DomPDF using `resources/views/students/id-card.blade.php`.
    - `StudentSubjectController` manages manual enrollment and dropping of subjects per student (admin-side UI for what the student portal later reads).
    - `ImportController` provides CSV-based bulk import of student records with validation and mapping from program code to `Program` IDs. It wraps inserts in a DB transaction and accumulates row-level errors that are surfaced back to the UI.

  - **Program & Subject Management**
    - `ProgramController` (resource `programs`) manages academic programs and their metadata; programs are used for filtering, reporting, and curriculum scoping.
    - `SubjectController` (resource `subjects`) manages curriculum; subjects reference `Program` and year/semester, and are used for enrollment rules, grade imports, and reports.

  - **Academic Year & Calendar Management**
    - `AcademicYearController` (resource `academic-years`) handles creation, editing, and deletion of academic years/semesters.
      - `store` auto-creates both 1st and 2nd semesters for a given `year_code`, with a shared set of date fields.
      - Changes (including when an academic year is set as current) are logged via `Activity` with before/after data in `properties[changes]`.
      - `setCurrent` ensures only one `AcademicYear` has `is_current = true` at a time and records that change in `Activity`.

  - **Archive System**
    - `ArchiveController` (routes under `/archive`) takes point-in-time snapshots of all `Student` rows into `ArchivedStudent` with denormalized keys (`archived_school_year`, `archived_semester`).
      - `index` lists archive batches grouped by school year/semester.
      - `create` displays live stats (counts of Active/Graduated students) to help admins decide when to archive.
      - `store` copies current `Student` records into `ArchivedStudent` inside a transaction; optionally truncates the `students` table if `delete_after_archive` is set.
      - `restore` re-creates an individual student from the archived JSON payload.
      - `destroy` deletes an entire archive batch for a year/semester.

  - **Semester Transition (Promotion & Retention)**
    - Admin routes under `prefix: semester-transition` use `Admin\SemesterTransitionController`, which delegates to `App\Services\SemesterTransitionService` and `AcademicStandingService`.
    - This subsystem is responsible for validating and executing progression between academic years:
      - **Preparation (`prepareYearTransition`)**
        - Scans only students with enrollments in the current `AcademicYear`.
        - Uses `AcademicStandingService::determineStanding` to compute each student’s academic standing and progression category:
          - `promoted_normal` — no failed subjects.
          - `promoted_irregular` — 1–2 failed subjects.
          - `retained` — 3+ failures.
          - `probation` — special case based on GWA.
        - Checks for pending, unapproved grade batches and incomplete (`INC`) grades, setting `ready`/`warnings`/`errors` flags and simple statistics (how many promoted/retained/probation).
      - **Execution (`executeYearTransition`)**
        - For each eligible student, calls `transitionStudent`, which:
          - Updates the student’s persisted academic standing and logs it.
          - For **normal promotion**: advances year level, auto-enrolls in all subjects for the next year level, or marks as Graduated if there is no higher-level curriculum.
          - For **irregular promotion**: advances year level (unless already at final year) and auto-enrolls both next-level subjects and retake enrollments for failed subjects via `getFailedSubjects`.
          - For **retained**: re-enrolls in all subjects of the same year level without advancing year.
          - **Probation**: does not auto-enroll; progression is handled manually.

  - **Reports & Export**
    - `ReportController` under `/reports` offers admin-facing reporting views:
      - `studentsList` — filtered list by program, year level, status (for on-screen inspection).
      - `programsList` — program-level overview with eager-loaded students sorted by year and name.
      - `yearLevelsList` — per-year-level breakdown of students
      - `exportStudents` — CSV export of students with core demographic and academic fields (student ID, name, program, year, status, enrollment date) using a streamed response.

  - **Activity Log / Audit Trail**
    - `ActivityLogController@index` (`/activities`) is a filterable index over `Activity` records.
      - Filters by action type, date range, and subject type; paginates results.
      - All key controllers write audit entries here so you can trace who changed what and when.

  - **User Management**
    - `UserController` manages staff/admin accounts that can log into the admin UI.

- **Admin Grade Workflows (`prefix: admin`)**

  - `AdminGradeApprovalController` (`admin/grade-approvals`) is the approval step for chairperson-uploaded batches.
    - Admins can review a `GradeImportBatch`, then approve or reject it; approvals apply grades to `Enrollment` rows, rejections update batch status and trigger notifications.
  - `AdminGradeModificationController` (`admin/grade-modifications`) allows controlled overrides of individual enrollment grades.
    - Provides index, edit, update, and history views so manual changes are auditable.
  - `Admin\GradeReportController` (`admin/reports`) uses `GwaCalculationService` and related helpers to:
    - Generate GWA reports.
    - List irregular students.
    - Produce dean's list and grade distribution views for quality assurance.

- **Chairperson Grade Management (`prefix: chairperson`)**

  - `Chairperson\GradeController` (`chairperson/grades`) centralizes subject- and student-centric grade entry UIs.
    - Supports per-student view, per-subject bulk entry, and per-enrollment edit/history.
    - Automatically recalculates student GWA after grade entry or modification.
  - `Chairperson\GradeImportController` (`chairperson/grade-import`) orchestrates Excel upload and mapping:
    - Uses `GradeImportService` plus `ColumnMapper` and `GradeNormalizer` to auto-detect or accept explicit column mappings (student ID, subject code, grade, etc.).
    - Creates `GradeImportBatch` and `GradeImportRecord` rows with detailed status (`matched` vs `error`) and per-row error messages.
    - Provides multi-step flows: create → mapping/preview → process → submit for admin approval.
  - `Chairperson\GradeBatchController` manages existing batches (`grade-batches`): list, show, retry failed processing, and delete.

### Service Layer Overview

Most domain rules that cut across multiple controllers live in `app/Services`:

- **`AcademicStandingService`**
  - Computes GWA (Philippine scale) per student and `AcademicYear`, using grade-to-weight mappings (1.00–5.00, `IP`, `INC`) and subject units.
  - Determines academic standing and progression bucket (`promoted_normal`, `promoted_irregular`, `retained`, `probation`) based on failed-subject count and GWA.
  - Persists `academic_standing`, `gwa`, and `is_irregular` on `Student` and writes log entries via `academicStandingLogs` on standing changes.

- **`GwaCalculationService`** (formerly `GpaCalculationService`)
  - Computes GWA (Grade Weighted Average) using Philippine grading scale (1.00-5.00) across all completed enrollments (cumulative by default).
  - Can calculate for a specific academic year if needed, or cumulative across all years.
  - Derives academic standing label (`good`, `probation`, `irregular`) based on GWA thresholds (≤1.75 excellent, ≤2.50 good, ≤3.00 probation, >3.00 irregular).
  - Updates the `Student` record with `gwa`, `academic_standing`, and `is_irregular` fields.
  - Provides batch GWA calculation and utility for finding all students affected by a set of enrollment IDs (e.g., after grade imports).
  - Automatically recalculates GWA when:
    - Admin approves grade import batches
    - Admin modifies individual grades
    - Chairperson enters or updates grades (both single and bulk entry)

- **`SemesterTransitionService`**
  - Coordinates with `AcademicStandingService` to prepare and execute bulk promotion/retention based on completed enrollments and grades.
  - Encodes year level as integers to simplify comparison, then updates `Student.year_level` and creates new `Enrollment` rows for next year’s curriculum or retakes.
  - Produces human-readable validation and result payloads (counts, error lists) consumed by the admin UI.

- **`GradeImportService`**
  - Parses Excel files using PhpSpreadsheet and a flexible column mapping strategy:
    - Auto-detects column positions or uses mapping provided by the UI.
    - Validates required fields and stores mapping JSON on `GradeImportBatch` for traceability.
  - For each row, validates student ID, subject code (scoped to the chairperson’s department), enrollment existence, and normalizes grade values via `GradeNormalizer`.
  - Creates `GradeImportRecord` rows with `status` (`matched` or `error`) and `error_message`, and updates batch-level counts and status (`ready` vs `pending` vs `failed`).

- **`NotificationService`**
  - Encapsulates creation of in-app notifications tied to grade batches and promotion events:
    - Batch uploaded/approved/rejected for chairpersons.
    - Irregular-student alerts and promotion summaries for admins.
  - Also provides convenience methods to fetch unread notifications and mark single/all notifications as read for a user.

### Frontend & Assets

- Frontend stack is Blade + Tailwind + Alpine.js, built via Vite:
  - Entry points are `resources/css/app.css` and `resources/js/app.js`, configured in `vite.config.js`.
  - `Chart.js` is available for dashboard charts and reports.
- Most admin and student UIs live under `resources/views/` with folders mirroring controllers:
  - `dashboard.blade.php`, `students/`, `programs/`, `subjects/`, `reports/`, `academic-years/`, `archive/`, `activities/`, `student/*`.
- Tailwind configuration lives in `tailwind.config.js`; PostCSS is configured via `postcss.config.js`.

### Testing & Seeded Scenarios

- There is an extensive set of test data and manual testing guidance:
  - `QUICK_REFERENCE.md`, `TESTING_CHECKLIST.md`, `TEST_SCENARIOS_REPORT.md`, and related docs describe more than 10 named scenario students (IDs like `2024-ACTIVE-001`, `2024-DROPOUT-001`, `2024-GRAD-001`, etc.) and how they exercise different statuses and edge cases.
  - These scenarios are seeded by the database seeders invoked by `php artisan migrate:fresh --seed`.
- When writing automated tests or debugging complex flows (enrollment, prerequisites, status changes, GPA/standing rules, archive/promotion edge cases), prefer to:
  - Reuse the seeded IDs and patterns from `QUICK_REFERENCE.md` and `TESTING_CHECKLIST.md`.
  - Assert on both functional behavior (e.g., enrollment blocked when prerequisites are missing) and audit/notification side effects (Activity records, Notification rows, updated academic standing).
