# SIMS Enrollment Validations - Final List

## Active Validations (7 Total)

### 1️⃣ STUDENT MUST BE ACTIVE
```
✅ Allows: Active only
❌ Blocks: On Leave, Graduated, Dropped, Transferred
```
**File**: `StudentSubjectController.php` Line 55-58

---

### 2️⃣ SUBJECT MUST BE ACTIVE
```
✅ Allows: is_active = true
❌ Blocks: is_active = false (discontinued courses)
```
**File**: `StudentSubjectController.php` Line 60-63

---

### 3️⃣ PROGRAM MATCH REQUIRED
```
✅ Allows: BSIT student taking BSIT course
❌ Blocks: BSIT student taking BSCrim course
```
**File**: `StudentSubjectController.php` Line 65-68

---

### 4️⃣ YEAR LEVEL: AT OR BELOW ONLY
```
✅ Allows: 2nd Year taking 1st or 2nd Year
❌ Blocks: 1st Year taking 3rd Year
```
**File**: `StudentSubjectController.php` Line 70-77

---

### 5️⃣ NO DUPLICATE ENROLLMENTS
```
✅ Allows: First enrollment in CS101
❌ Blocks: Second enrollment in CS101 (same academic year)
```
**File**: `StudentSubjectController.php` Line 79-87

---

### 6️⃣ CREDIT HOUR LIMITS (12-18 Units)
```
✅ Allows: 3 + 12 = 15 units
❌ Blocks: 10 + 12 = 22 units (exceeds 18 max)
```
**File**: `StudentSubjectController.php` Line 89-106

---

### 7️⃣ REGISTRATION PERIOD ONLY
```
✅ Allows: Aug 1-15 (within registration dates)
❌ Blocks: Before Aug 1 or After Aug 15
```
**File**: `StudentSubjectController.php` Line 108-117

---

## REMOVED (Grading System Not Implemented)

### ❌ PREREQUISITE VALIDATION (REMOVED)
- **Reason**: No grading system yet - can't validate "completed with grade >= 60"
- **File Removed From**: `Subject.php` (hasMetPrerequisites method)
- **File Removed From**: `StudentSubjectController.php` (prerequisite check)

### ❌ NO RETAKING PASSED COURSES (REMOVED)
- **Reason**: No grading system yet - can't track which courses were passed
- **File Removed From**: `StudentSubjectController.php`

### ❌ GPA CALCULATION (REMOVED)
- **Method**: `Student::calculateGPA()` - REMOVED
- **Reason**: No grades exist yet, no need to calculate GPA
- **File**: `Student.php`

### ❌ COMPLETED UNITS TRACKING (REMOVED)
- **Method**: `Student::completedUnits()` - REMOVED
- **Reason**: No grading system yet
- **File**: `Student.php`

---

## Current Enrollment Rules (Simple & Clean)

Students can enroll in a course if:
1. ✅ Student status is "Active"
2. ✅ Subject is marked "active" (not discontinued)
3. ✅ Subject belongs to their program
4. ✅ Subject year level ≤ their year level
5. ✅ Not already enrolled in this subject (same year)
6. ✅ Adding course wouldn't exceed 18 units/semester
7. ✅ Within the registration period

---

## Future Enhancements (When Grading System Added)

When you add grading functionality, re-add:
1. **Prerequisite validation** - Validate completed courses with grades
2. **No retake logic** - Prevent retaking passed courses
3. **GPA calculation** - Calculate from grades
4. **Academic standing** - Determine probation/suspension based on GPA

---

## Database Schema (Grade Fields Remain)

The `enrollments` table still has:
```sql
- grade (decimal, nullable) - For future use
- remarks (text, nullable) - For future use
```

These are **NOT validated or populated** until grading system is implemented.

---

## Enrollment Data

- **Status**: All current enrollments are `'Enrolled'`
- **Grades**: ALL are `NULL` (no grading system)
- **Remarks**: ALL are `NULL` (no grading system)

---

**Last Updated**: Nov 26, 2025  
**Grading System**: ❌ NOT IMPLEMENTED  
**Status**: ✅ PRODUCTION READY (7 validations active)
