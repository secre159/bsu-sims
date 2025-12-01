# Quick Reference - BSU SIMS Test Data

## Test Scenario Students

### Core Scenarios (13 Students)

| Student ID | Name | Status | Year | Enrollments | Key Feature |
|-----------|------|--------|------|-------------|-------------|
| 2024-ACTIVE-001 | Scholar Active | Active | 1st | 4 Enrolled | Basic active student |
| 2024-DROPOUT-001 | CourseD Quitter | Active | 1st | 4 Enrolled, 1 Dropped | Drop history |
| 2024-FAILED-001 | UnluckyD Flunked | Active | 2nd | 3 Enrolled, 1 Failed (4.0) | Failed grade |
| 2024-COMPLETED-001 | Course Finished | Active | 2nd | 4 Completed, 1 Enrolled | Prerequisites satisfied |
| 2024-LEAVE-001 | OnLeave Absent | On Leave | 3rd | None | No enrollments |
| 2024-GRAD-001 | Graduate Valedictorian | Graduated | 4th | 10 Completed | Highest year level |
| 2024-DROPPED-001 | Program Discontinued | Dropped | 1st | 2 Dropped | Dropped student |
| 2024-2NDYEAR-001 | Level Year2nd Year | Active | 2nd | 4 Enrolled | 2nd year |
| 2024-3RDYEAR-001 | Level Year3rd Year | Active | 3rd | 4 Enrolled | 3rd year |
| 2024-4THYEAR-001 | Level Year4th Year | Active | 4th | 4 Enrolled | 4th year |
| 2024-5THYEAR-001 | Level Year5th Year | Active | 5th | 4 Enrolled | 5th year |
| 2024-PROGRESS-001 | Career Progressor | Active | 4th | 0-5 varies | Status transitions logged |

**Plus:** 50+ additional students from original seeder with various names and statuses

---

## Quick Access Guide

### Start the App
```bash
php artisan serve
# Navigate to http://localhost:8000
# Login: admin@bsu-bokod.edu.ph
```

### Reset Database
```bash
# Full reset with all scenarios
php artisan migrate:fresh --seed

# Just refresh audit trail
php artisan db:seed --class=SampleActivitiesSeeder
```

### Query Test Data
```bash
# View all test students
php artisan tinker
>>> Student::where('student_id', 'LIKE', '2024-%')->get(['student_id', 'first_name', 'status', 'year_level']);

# View audit trail
>>> Activity::latest()->limit(20)->get(['action', 'description', 'created_at']);

# View specific student's activities
>>> $s = Student::where('student_id', '2024-ACTIVE-001')->first();
>>> Activity::where('subject_type', 'App\\Models\\Student')->where('subject_id', $s->id)->get();
```

---

## Test Scenarios by Purpose

### Testing Student Statuses
- **Active:** 2024-ACTIVE-001, 2024-ACTIVE-002 (and year-level variants)
- **On Leave:** 2024-LEAVE-001
- **Graduated:** 2024-GRAD-001
- **Dropped:** 2024-DROPPED-001

### Testing Enrollment States
- **Enrolled:** All ACTIVE students (multiple)
- **Completed:** 2024-COMPLETED-001 (4 courses completed)
- **Dropped:** 2024-DROPOUT-001 (1 dropped), 2024-DROPPED-001 (2 dropped)
- **Failed:** 2024-FAILED-001 (grade 4.0)

### Testing Prerequisites
- **Has Prerequisite:** 2024-COMPLETED-001 successfully enrolled in CS102
- **Missing Prerequisite:** Try enrolling others in CS102 (should fail)
- **Prerequisite Chain:** CS101 → CS102 → CS201

### Testing Constraints
- **Graduation Validation:** 2024-GRAD-001 is 4th year (allowed)
  - Try to graduate 2024-ACTIVE-001 (1st year - should fail)
- **Inactive Subjects:** BIT101 marked inactive
- **Enrollment Restrictions:** 2024-LEAVE-001 has no enrollments
- **Drop Deadline:** Check remarks on dropped courses

### Testing Year Levels
- **1st Year:** 2024-ACTIVE-001, 2024-DROPOUT-001, 2024-DROPPED-001
- **2nd Year:** 2024-FAILED-001, 2024-COMPLETED-001, 2024-2NDYEAR-001
- **3rd Year:** 2024-LEAVE-001, 2024-3RDYEAR-001
- **4th Year:** 2024-GRAD-001, 2024-4THYEAR-001, 2024-PROGRESS-001
- **5th Year:** 2024-5THYEAR-001

---

## Audit Trail Sample Activities

**Total: 32+ activities logged**

### By Type
- **Created:** 6 students + 5 subjects + 3 programs = 14 activities
- **Enrolled:** 15 enrollment actions
- **Updated:** 3 status transitions + subject deactivation = 4 activities
- **Dropped, Completed, Failed:** 1+ each

### What's Tracked
✅ Student creation (student_id, program, year level)
✅ Student updates (changes with old/new values)
✅ Enrollment actions (student → subject)
✅ Course drops (timestamp captured)
✅ Subject creation/deactivation
✅ Program creation
✅ Status transitions (with before/after values)

---

## Pages to Test

### Students (`/students`)
- [ ] View list (filter by status, program, year level)
- [ ] Click on 2024-ACTIVE-001 → view profile
- [ ] Click on 2024-DROPOUT-001 → see mixed statuses
- [ ] Click on 2024-LEAVE-001 → see no enrollments
- [ ] Click on 2024-GRAD-001 → see all completed

### Enrollments
- [ ] Click "View Enrollments" on 2024-ACTIVE-001
- [ ] See 4 "Enrolled" courses
- [ ] Try to drop one (should show "Dropped" status)
- [ ] Check remarks for drop timestamp
- [ ] Verify 2024-DROPOUT-001 shows dropped course

### Audit Trail (`/activities`)
- [ ] See 32+ activities
- [ ] Sort by newest/oldest
- [ ] Check descriptions are clear
- [ ] Verify timestamps recorded
- [ ] Look for enrollment actions

### Status/Validation
- [ ] Edit 2024-GRAD-001 → status shows Graduated
- [ ] Edit 2024-ACTIVE-001 → try to mark as Graduated
  - Should show error (1st year can't graduate)
- [ ] Edit 2024-LEAVE-001 → can change to Active
- [ ] Verify audit trail logs changes

### Prerequisites
- [ ] 2024-COMPLETED-001 → view CS102 enrollment
  - Has CS101 completed (prerequisite satisfied)
- [ ] Try enrolling another student in CS102
  - Should fail (no CS101)

---

## Common Findings

### Expected to Work ✅
- View all test students
- Filter by status/program/year level
- See enrollment history (with drop timestamps)
- See grades on completed/failed courses
- View audit trail
- See status transitions logged

### Might Need Fixing ⚠️
- Grade entry UI (if not implemented)
- GPA calculation (if not implemented)
- Transcript generation (if not implemented)
- Activity filtering by date range
- Bulk status updates
- Program change logic

---

## Files Created for Testing

| File | Purpose |
|------|---------|
| `TEST_SCENARIOS_REPORT.md` | Detailed breakdown of 10 scenarios |
| `TESTING_CHECKLIST.md` | Step-by-step checklist for verification |
| `IMPLEMENTATION_SUMMARY.md` | What was implemented and tested |
| `QUICK_REFERENCE.md` | This file - quick lookup guide |

---

## Database Stats

- **Students:** 60+
- **Subjects:** 30+ (1 inactive)
- **Enrollments:** 200+
- **Activities:** 32+
- **Programs:** 8-10
- **Academic Years:** 3

---

## Tips for Testing

1. **Start with scenario students** (2024-ACTIVE-001, etc.)
2. **Follow the testing checklist** systematically
3. **Check audit trail** after each action
4. **Try invalid operations** (should fail gracefully)
5. **Document what you find** (expected vs. actual)
6. **Screenshot errors** for reporting

---

## Support Queries

### Find a student's audit trail
```php
$student = Student::where('student_id', '2024-ACTIVE-001')->first();
Activity::where('subject_id', $student->id)
    ->where('subject_type', 'App\Models\Student')
    ->latest()
    ->get();
```

### Count activities by type
```php
Activity::groupBy('action')
    ->selectRaw('action, count(*) as count')
    ->get();
```

### Find enrollment logs for a student
```php
$student = Student::where('student_id', '2024-ACTIVE-001')->first();
Activity::where('subject_type', 'App\Models\Enrollment')
    ->whereJsonContains('properties->student_id', $student->id)
    ->get();
```

---

**Last Updated:** 2025-11-27
**Ready to Test!** 🎯
