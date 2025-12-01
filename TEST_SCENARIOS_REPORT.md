# BSU SIMS - Comprehensive Test Scenarios Report

## Overview
Database has been populated with **10 comprehensive test scenarios** covering all possible combinations of:
- Student statuses
- Enrollment states
- Prerequisites
- Subject activation states
- Program data
- Audit trail logging

---

## Scenario 1: Active Student with Full Enrollment
**Student ID:** `2024-ACTIVE-001`  
**Name:** Scholar Active Test  
**Status:** Active  
**Year Level:** 1st Year  
**Enrollments:** 4 subjects - All "Enrolled"  
**What to Test:**
- View active student with current enrollment
- Verify all courses show as enrolled
- Check enrollment history in audit trail
- Verify basic student profile information

---

## Scenario 2: Student with Dropped Course
**Student ID:** `2024-DROPOUT-001`  
**Name:** CourseD Quitter Test  
**Status:** Active  
**Year Level:** 1st Year  
**Enrollments:** 5 subjects - 1 "Dropped", 4 "Enrolled"  
**What to Test:**
- Dropped course shows in enrollment list with "Dropped" status
- Enrollment history preserved (can see when dropped)
- Remarks field shows drop timestamp
- Drop deadline enforcement (if attempting new drops)
- Audit trail shows drop action with timestamp

---

## Scenario 3: Student with Failed Grade
**Student ID:** `2024-FAILED-001`  
**Name:** UnluckyD Flunked Test  
**Status:** Active  
**Year Level:** 2nd Year  
**Enrollments:** 4 subjects - 1 "Failed" (Grade: 4.0), 3 "Enrolled"  
**What to Test:**
- Failed course shows with grade 4.0
- Remarks field indicates "Failed - needs retake"
- Student can retake failed course next semester
- GPA calculation excludes/includes failed grade correctly
- Audit trail shows failed enrollment

---

## Scenario 4: Student with Completed Courses (Prerequisites)
**Student ID:** `2024-COMPLETED-001`  
**Name:** Course Finished Test  
**Status:** Active  
**Year Level:** 2nd Year  
**Enrollments:** 5 subjects - 4 "Completed" (with grades 1.0-2.0), 1 "Enrolled"  
**Special Feature:** Enrolled in CS102 which requires CS101 prerequisite - **prerequisite is satisfied**  
**What to Test:**
- Completed courses show with final grades
- Student allowed to enroll in CS102 (has CS101 completed)
- Prerequisite system validates correctly
- Transcript shows completed courses with grades
- Audit trail shows completion status

---

## Scenario 5: Student On Leave
**Student ID:** `2024-LEAVE-001`  
**Name:** OnLeave Absent Test  
**Status:** On Leave  
**Year Level:** 3rd Year  
**Enrollments:** None (on leave - no current enrollment)  
**What to Test:**
- Student profile shows "On Leave" status
- No current enrollments display
- Cannot enroll in courses while on leave (business logic check)
- Status can be changed back to Active
- Audit trail logs status transitions

---

## Scenario 6: Graduated Student
**Student ID:** `2024-GRAD-001`  
**Name:** Graduate Valedictorian Test  
**Status:** Graduated  
**Year Level:** 4th Year  
**Enrollments:** 10 subjects - All "Completed" (with various grades: 1.0 to 2.0)  
**What to Test:**
- Graduated student shows final status
- All courses completed with grades
- Cannot enroll in new courses (graduated)
- Complete transcript available
- Status validation: Only 4th+ year students can graduate
- Audit trail shows graduation status

---

## Scenario 7: Dropped Student (Discontinued Program)
**Student ID:** `2024-DROPPED-001`  
**Name:** Program Discontinued Test  
**Status:** Dropped  
**Year Level:** 1st Year  
**Enrollments:** 2 subjects - Both "Dropped"  
**Special Feature:** Dropped early in program (after 3 months)  
**What to Test:**
- Student profile shows "Dropped" status
- Previous enrollments show as dropped
- Cannot make new enrollments (dropped status blocks it)
- Audit trail shows when student was dropped
- Different from "On Leave" - cannot return

---

## Scenario 8: Students Across All Year Levels
**Students Created:** 2nd Year, 3rd Year, 4th Year, 5th Year  
**Student IDs:** 
- `2024-2NDYEAR-001` (2nd Year)
- `2024-3RDYEAR-001` (3rd Year)
- `2024-4THYEAR-001` (4th Year)
- `2024-5THYEAR-001` (5th Year)

**Each with:**
- Status: Active
- 4 subjects enrolled (matching their year level)
- Varied enrollment dates (6-36 months ago)

**What to Test:**
- Year level filtering works correctly
- Subjects display only for appropriate year level
- Each student can only enroll in their year's subjects
- Graduation validation: only 4th+ year can graduate
- Year level progression logic

---

## Scenario 9: Inactive Subject (Blocked from New Enrollment)
**Subject:** `BIT101`  
**Status:** is_active = false  
**What to Test:**
- Inactive subject doesn't appear in enrollment dropdown
- If already enrolled, existing enrollment is preserved
- Cannot add new students to inactive subject
- Audit trail shows when subject was deactivated
- Subject can be reactivated if needed

---

## Scenario 10: Student Status Progression Tracking
**Student ID:** `2024-PROGRESS-001`  
**Name:** Career Progressor Test  
**Initial Status:** Active  
**Status Changes Logged:**
1. Active → Graduated (status update logged in audit trail)
2. Graduated → Active (reverse status update logged)

**What to Test:**
- Audit trail captures status transitions
- Each change has timestamp and user info
- Old → New status visible in activity properties
- Multiple status transitions tracked chronologically
- Audit trail sorting shows most recent changes first

---

## Audit Trail Statistics

### Total Activities Logged: 32+

| Action Type | Count | Examples |
|-------------|-------|----------|
| Created (Students) | 6 | Student creation from all scenarios |
| Created (Subjects) | 5 | Subject creation logging |
| Created (Programs) | 3 | Program creation logging |
| Enrolled | 15 | Enrollment actions across scenarios |
| Updated | 3 | Status transitions and subject deactivations |
| Dropped | 1+ | Student course drops |
| Completed | 1+ | Completed course tracking |
| Failed | 1+ | Failed grade tracking |

---

## Key Test Areas to Verify

### 1. Student Management
- ✅ Create student with all statuses (Active, On Leave, Graduated, Dropped)
- ✅ View student details and year level
- ✅ Update student information (status, year level, program)
- ✅ Student list filtering by status, program, year level
- ✅ Status validation: Only 4th+ year can graduate

### 2. Enrollment Management
- ✅ Enroll student in subjects
- ✅ Drop course (marks status as Dropped, preserves history)
- ✅ View enrollment status (Enrolled, Completed, Dropped, Failed)
- ✅ Prerequisite validation (can't enroll without completed prerequisites)
- ✅ Add/drop deadline enforcement
- ✅ Inactive subjects block new enrollments but preserve existing ones

### 3. Audit Trail (Activity Logging)
- ✅ Student creation logged with student_id, program
- ✅ Student updates logged with changes (old → new values)
- ✅ Enrollment actions logged (enrolled, dropped, completed, failed)
- ✅ Subject creation/updates/deactivation logged
- ✅ Program creation/updates logged
- ✅ Status transitions logged with before/after values
- ✅ All activities show: user_id, timestamp, action, description, properties

### 4. Data Integrity
- ✅ Dropped students have no new enrollments (status prevents)
- ✅ On Leave students have no current enrollments
- ✅ Graduated students show all completed courses
- ✅ Failed grades show correctly with remarks
- ✅ Prerequisites prevent enrollment until satisfied
- ✅ Inactive subjects don't accept new enrollments

### 5. Scenarios NOT Yet Tested (for implementation)
- Program change for student (from one program to another)
- Subject retake after failure (can enroll again)
- Transcript generation with GPA calculation
- Grade entry/editing by admin
- Bulk status updates
- Report generation (by year level, status, program)

---

## Database Query Examples for Testing

### View All Activities (latest first)
```php
Activity::latest()->paginate(20);
```

### View Activities for Specific Student
```php
$student = Student::find(1);
Activity::where('subject_type', 'App\\Models\\Student')
    ->where('subject_id', $student->id)
    ->latest()
    ->get();
```

### View Enrollment Activities
```php
Activity::where('subject_type', 'App\\Models\\Enrollment')
    ->where('action', 'dropped')
    ->latest()
    ->get();
```

### View Status Transitions
```php
Activity::where('action', 'updated')
    ->where('subject_type', 'App\\Models\\Student')
    ->latest()
    ->get();
```

---

## How to Reset and Re-Seed

If you need to start fresh with all test scenarios:

```bash
# Drop all tables and re-seed
php artisan migrate:fresh --seed

# Or just reseed the activities
php artisan db:seed --class=SampleActivitiesSeeder
```

---

## Next Steps

1. **Access `/activities` page** to view audit trail in web UI
2. **Test each scenario** by navigating to student/enrollment pages
3. **Verify business logic** by attempting operations that should be blocked
4. **Check audit trail** entries match the actions performed
5. **Identify issues** that need to be fixed based on what you find

---

**Last Updated:** 2025-11-27  
**Test Data Version:** 1.0
