# Student Portal Fixes - December 5, 2025

## Issues Fixed

### 1. Incorrect Semester Ordering
**Problem**: Semesters were displaying in the wrong order - newer semesters appeared at the bottom instead of the top.

**Root Cause**: The enrollments were being ordered by `academic_year_id`, but the database IDs were not in chronological order. The current semester (2025-2026-1) had ID 3, while older semesters (2024-2025-1) had ID 10.

**Solution**: Changed the ordering to sort by `year_code` in descending order (newest first) instead of relying on database IDs.

**Files Modified**:
- `app/Http/Controllers/Student/StudentDashboardController.php`
- `app/Http/Controllers/Student/StudentEnrollmentController.php`

**Code Changes**:
```php
// Before: Ordered by database ID
->orderBy('academic_year_id', 'desc')

// After: Sorted by year code
$allEnrollments = $allEnrollments->sortByDesc(function ($enrollment) {
    return $enrollment->academicYear->year_code ?? '';
});
```

### 2. Missing Year Level Indicator
**Problem**: Semester labels didn't show which year level the subjects belonged to, making it confusing especially when a student had multiple semesters with the same name (e.g., "1st Semester" could be 1st year or 2nd year).

**Solution**: Added year level to the semester grouping label.

**Display Format**:
- **Before**: `2025-2026-1 - 1st Semester`
- **After**: `2025-2026-1 - 1st Semester (2nd Year)`

**Code Changes**:
```php
// Before
return $enrollment->academicYear->year_code . ' - ' . $enrollment->academicYear->semester;

// After
$yearLevel = $enrollment->subject->year_level;
return $enrollment->academicYear->year_code . ' - ' . $enrollment->academicYear->semester . ' (' . $yearLevel . ')';
```

### 3. Grade Status Display Logic
**Issue Clarification**: The "Ongoing" status was showing correctly for enrolled subjects with no grades. The confusion was due to the incorrect ordering.

**Current Status Logic** (No changes needed):
- `Enrolled` status + no grade → Shows "Ongoing" (Blue)
- `Completed` status + has grade → Shows "Passed" (Green)
- `Failed` status or grade ≥ 4.0 → Shows "Failed" (Red)
- `Dropped` status → Shows "Dropped" (Gray)
- Grade = 'INC' → Shows "Incomplete" (Amber)
- Grade = 'IP' → Shows "In Progress" (Blue)

## Current Display Order (Example: Juan Dela Cruz)

Now displays in correct chronological order (newest first):

1. **2025-2026-1 - 1st Semester (2nd Year)** - 7 subjects
   - Status: Enrolled
   - Grades: Not yet available (shows "Ongoing")

2. **2024-2025-2 - 2nd Semester (2nd Year)** - 6 subjects
   - Status: Completed
   - Grades: Available (shows "Passed")

3. **2024-2025-1 - 1st Semester (2nd Year)** - 7 subjects
   - Status: Completed
   - Grades: Available (shows "Passed")

4. **2023-2024-2 - 2nd Semester (1st Year)** - 4 subjects
   - Status: Completed
   - Grades: Available (shows "Passed")

## Testing

To test the fixes:

1. Log into the student portal:
   - Email: `juan.delacruz@student.bsu-bokod.edu.ph`
   - Password: `BSU-0012005`

2. Verify the dashboard shows semesters in correct order (newest first)
3. Verify each semester label includes the year level
4. Verify current semester shows "Ongoing" for subjects without grades
5. Verify past semesters show grades and "Passed" status

## Affected Pages

- Student Dashboard (`/student/dashboard`)
- Student Enrollments (`/student/enrollments`)

Both pages now display semesters consistently with year level indicators and correct chronological ordering.
