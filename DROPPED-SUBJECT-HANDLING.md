# How SIMS Handles Dropped Subjects

## Two Types of "Dropped"

The system distinguishes between two different scenarios:

### 1. **Subject Dropped from Curriculum** (Admin Action)
When an admin marks a subject as inactive (`is_active = false`)

### 2. **Student Drops a Course** (Student Action)
When a student withdraws from an enrolled course

---

## Scenario 1: Subject Dropped from Curriculum

### What Happens When Admin Sets `is_active = false`?

**Immediate effects:**
- Subject no longer appears in "Available Courses" list
- Subject cannot be used for new enrollments
- Error: "Cannot enroll: {Subject} is no longer offered (dropped from curriculum)"

### Code Location
`app/Http/Controllers/StudentSubjectController.php` (line 61)

```php
// VALIDATION 2: Check subject is active
if (!$subject->is_active) {
    return back()->with('error', "Cannot enroll: {$subject->name} is no longer offered (dropped from curriculum).");
}
```

### Example Scenario

```
Timeline:
├─ Jan 1: CS101 is offered (is_active = true)
│  └─ Students can enroll
│
├─ Feb 15: Admin drops CS101 (is_active = false)
│  └─ New students can no longer enroll
│
├─ Feb 16: New student tries to enroll in CS101
│  └─ ❌ ERROR: "CS101 is no longer offered (dropped from curriculum)"
│
└─ Existing enrollments?
   └─ ✅ Already enrolled students keep their enrollment
   └─ They can still drop it if within deadline
   └─ Grade still counts toward transcript
```

### Impact on Prerequisites

If CS101 is dropped and CS201 requires CS101:
```
├─ Existing students with completed CS101
│  └─ ✅ Can still enroll in CS201 (prerequisite already met)
│
└─ New students who never took CS101
   └─ ❌ Cannot enroll in CS201
   └─ CS101 no longer available to complete prerequisite
```

**⚠️ Business Logic Issue:** This creates an unfair situation. New students can't complete prerequisites.

**Recommendation:** Before dropping a course, ensure:
1. ✅ No pending students need it as a prerequisite
2. ✅ Or offer it one more semester as "Retake Only"
3. ✅ Or create alternative course to replace it

---

## Scenario 2: Student Drops a Course

### What Happens When Student Clicks "Drop"?

**Before (Old System - Line 163):**
```php
$enrollment->delete();  // ❌ Deletes record completely
```

**After (New System - Line 172-173):**
```php
$enrollment->status = 'Dropped';  // ✅ Mark as Dropped
$enrollment->remarks = 'Dropped on 2025-11-27';
$enrollment->save();  // ✅ Preserve history
```

### Why Change from Delete to Mark?

| Aspect | Delete | Mark as Dropped |
|--------|--------|-----------------|
| History | Lost forever ❌ | Preserved ✅ |
| Transcript | No record ❌ | Shows student enrolled ✅ |
| Audit Trail | Can't track changes ❌ | Complete audit trail ✅ |
| Reporting | Missing data ❌ | Accurate reports ✅ |
| Retake Options | Unclear if retaken ❌ | Clear if retaken ✅ |

### Drop Deadline Enforcement

**New Validation (Line 165-169):**
```php
if ($currentAcademicYear->add_drop_deadline) {
    if (now() > $currentAcademicYear->add_drop_deadline) {
        return back()->with('error', 'Add/Drop deadline has passed. Contact the Registrar.');
    }
}
```

### Example Scenario

```
Timeline:
├─ Nov 20: Student enrolls in CS101
│  └─ Status: Enrolled
│
├─ Nov 25: Add/Drop deadline set in academic calendar
│  └─ This is the last day to drop
│
├─ Nov 27: Student clicks "Drop Course" (BEFORE deadline)
│  └─ ✅ SUCCESS
│  └─ Enrollment status changed to "Dropped"
│  └─ Remarks: "Dropped on 2025-11-27"
│  └─ Record preserved in database
│
└─ Dec 1: Student tries to drop a different course (AFTER deadline)
   └─ ❌ ERROR: "Add/Drop deadline has passed. Contact the Registrar."
   └─ Student cannot drop without registrar approval
```

### Database Result After Dropping

**Enrollment record stays:**
```
| student_id | subject_id | status  | remarks             |
|------------|-----------|---------|---------------------|
| 5          | 1         | Dropped | Dropped on 2025-11-27|
```

**Not deleted:**
```
| student_id | subject_id | ... | (record exists, not deleted) |
```

---

## Impact on System Calculations

### GPA Calculation
If GPA includes dropped courses, admin must decide:

**Option A: Exclude dropped courses**
```php
$enrollments = $student->enrollments()
    ->whereNotIn('status', ['Dropped'])  // Exclude
    ->get();
```

**Option B: Include dropped courses (count as 0.0)**
```php
$enrollments = $student->enrollments()
    ->get();  // Include all, but dropped = 0 points
```

### Unit Requirements
When checking if student completed units:

```php
$completedUnits = $student->enrollments()
    ->where('status', 'Completed')  // Only completed
    ->sum('subject.units');

// Dropped courses DON'T count toward requirements
```

### Retake Logic
If student retakes a course after dropping:

```
├─ First enrollment: Status = Dropped
├─ Second enrollment: Status = Enrolled (new record)
└─ Both records preserved on transcript
   └─ Shows student retook the course
```

---

## Validation During Drop

The system now validates:

✅ **Ownership** - Student can only drop their own enrollment
✅ **Deadline** - Only allow drops before add/drop deadline
✅ **Notification** - Clear message if deadline passed
✅ **History** - Mark as Dropped, don't delete

---

## What Enrollment Status Values Mean

| Status | Meaning | Can Drop? | Counts Toward GPA? | Counts Toward Units? |
|--------|---------|-----------|-------------------|---------------------|
| Enrolled | Currently taking | ✅ Yes (if before deadline) | ❌ No (not done) | ❌ No |
| Completed | Passed the course | ❌ No | ✅ Yes | ✅ Yes |
| Dropped | Withdrew from course | N/A | ❌ No | ❌ No |
| Failed | Did not pass | ❌ No | ✅ Yes (as 0.0) | ❌ No |

---

## Reporting Impact

### Transcript Shows:
```
CS101 - Introduction to Programming
├─ Semester: 2024-2025-1st
├─ Status: Dropped
├─ Units: 3
├─ Remarks: Dropped on 2025-11-27
└─ Note: Does NOT appear in GPA or unit counts
```

### Student Reports:
```
Current Semester Enrollments:
├─ CS101 - Dropped on Nov 27 (3 units)
├─ CS102 - Enrolled (3 units)
└─ Total Active Units: 3 (dropped courses excluded)
```

### Admin Reports:
```
Drop Statistics for 2024-2025-1st:
├─ Total Enrollments: 237
├─ Dropped: 12 (5%)
├─ Completed: 200 (84%)
├─ Enrolled: 25 (11%)
└─ Helps identify struggling students or unpopular courses
```

---

## Database Schema

The system uses existing fields:

```sql
enrollments table:
├─ id
├─ student_id
├─ subject_id
├─ academic_year_id
├─ status (Enrolled, Completed, Dropped, Failed)
├─ grade (NULL if Dropped)
├─ remarks (e.g., "Dropped on 2025-11-27")
├─ created_at
└─ updated_at
```

---

## Summary

### Subject Dropped (Admin)
- Subject marked `is_active = false`
- No new enrollments allowed
- Existing enrollments preserved
- Prerequisites may become unavailable

### Student Drops Course
- Enrollment status changed to "Dropped"
- Record preserved (not deleted)
- Add/drop deadline enforced
- Doesn't count toward GPA or units
- Preserves audit trail

**Both scenarios maintain data integrity and audit history.**
