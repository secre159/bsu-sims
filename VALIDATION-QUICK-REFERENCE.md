# SIMS Validation Rules - Quick Reference

## 9 Critical Business Rules Now Enforced

### 1️⃣ STUDENT MUST BE ACTIVE
```
❌ Blocks: On Leave, Graduated, Dropped, Transferred
✅ Allows: Active only
Message: "Cannot enroll: Student status is 'On Leave'. Only Active students can register."
```

### 2️⃣ CREDIT HOURS: 12-18 UNITS MAX
```
✅ Allows: 3 + 12 = 15 units (OK)
❌ Blocks: 10 + 12 = 22 units (exceeds 18 max)
Message: "Would exceed maximum 18 units. Current: 10, Adding: 12, Total would be: 22"
```

### 3️⃣ PREREQUISITES: GRADE 60+
```
✅ Allows: Completed PROG102 with grade 75
❌ Blocks: Completed PROG102 with grade 55
❌ Blocks: Never attempted PROG102
Message: "Must complete prerequisite(s) with passing grade"
```

### 4️⃣ YEAR LEVEL: AT OR BELOW ONLY
```
✅ Allows: 2nd Year student taking 2nd Year course
✅ Allows: 2nd Year student taking 1st Year course
❌ Blocks: 1st Year student taking 3rd Year course
Message: "[Course] is for [Year] students. You are [Student Year]."
```

### 5️⃣ PROGRAM MATCH REQUIRED
```
✅ Allows: BSIT student taking BSIT course
❌ Blocks: BSIT student taking BSCrim course
Message: "[Course] is not offered in BSIT program."
```

### 6️⃣ NO DUPLICATE ENROLLMENTS
```
✅ Allows: First time enrolling in CS101
❌ Blocks: Second enrollment in CS101 (same academic year)
Message: "Student is already enrolled in this subject for the current academic year!"
```

### 7️⃣ NO RETAKING PASSED COURSES
```
✅ Allows: Retaking failed course (grade < 60)
❌ Blocks: Retaking passed course (grade >= 60)
Message: "Student already passed this subject. Retaking not allowed."
```

### 8️⃣ SUBJECT MUST BE ACTIVE
```
✅ Allows: is_active = true
❌ Blocks: is_active = false (discontinued course)
Message: "[Course Name] is no longer offered."
```

### 9️⃣ REGISTRATION PERIOD ONLY
```
✅ Allows: Aug 1-15 (within registration dates)
❌ Blocks: Before Aug 1 ("Registration not yet open")
❌ Blocks: After Aug 15 ("Registration period has closed")
```

---

## Where Each Rule Is Checked

| Rule | File | Method | Line |
|------|------|--------|------|
| Status | Controller | enroll() | 55 |
| Credits | Controller | enroll() | 107 |
| Prerequisites | Model | hasMetPrerequisites() | 61 |
| Year Level | Controller | enroll() | 70 |
| Program | Controller | enroll() | 65 |
| Duplicate | Controller | enroll() | 86 |
| No Retake | Controller | enroll() | 96 |
| Subject Active | Controller | enroll() | 60 |
| Registration | Controller | enroll() | 126 |

---

## Helper Methods Available

```php
// Check if student can register
$student->canRegister()  // Returns: boolean

// Calculate GPA (0.0-4.0)
$student->calculateGPA()  // Returns: float

// Get completed units
$student->completedUnits()  // Returns: integer
```

---

## Test Scenarios

### ✅ PASS: Normal Enrollment
```
Maria (BSIT, 2nd Year, Active)
+ Taking PROG102 (3 units)
+ Has PROG101 completed with grade 75
+ Program matches
+ Year level 2nd >= 1st Year course
+ Total would be 9 units (< 18)
= ENROLLED ✅
```

### ❌ FAIL: Student On Leave
```
Juan (BTVTEd, 4th Year, On Leave)
+ Status = "On Leave"
= ERROR: Cannot enroll ❌
```

### ❌ FAIL: Credit Overload
```
Ana (BSEd, 3rd Year, Active)
+ Currently: 15 units
+ Trying to add: 4-unit course
+ Total would be: 19 units
= ERROR: Exceeds 18 max ❌
```

### ❌ FAIL: Failed Prerequisite
```
Jose (BSIT, 2nd Year, Active)
+ Taking: OOP201
+ Prerequisite: PROG102
+ Jose's PROG102 grade: 55
= ERROR: Grade below 60 ❌
```

### ❌ FAIL: Wrong Program
```
Carlos (BSIT, 2nd Year, Active)
+ Taking: CRIM101
+ CRIM101 belongs to: BSCrim
+ Carlos belongs to: BSIT
= ERROR: Not in program ❌
```

---

## Configuration

**Academic Year Calendar** (Set in Seeder):
```
Semester 1 (2024-2025-1):
  Registration Open: Aug 1 - Aug 15
  Classes Start: Aug 22
  Add/Drop Deadline: Aug 29

Semester 2 (2024-2025-2):
  Registration Open: Dec 15 - Jan 5
  Classes Start: Jan 15
  Add/Drop Deadline: Jan 26
```

**Grade Passing Threshold**: 60+

**Maximum Units**: 18 per semester

**Minimum Units**: 12 for full-time status (enforced separately)

---

## For Developers

### To Add New Validation

1. Open: `app/Http/Controllers/StudentSubjectController.php`
2. Add check in `enroll()` method before line 139
3. Return back()->with('error', 'Your message')
4. Document in this file

### To Modify Existing Validation

1. Locate in table above
2. Edit file/method shown
3. Test with scenarios
4. Update this reference

### To Test

```bash
# Run migrations
php artisan migrate:fresh --seed

# Start server
php artisan serve

# Navigate to Students > Select Student > View Subjects > Try to Enroll
```

---

**Last Updated**: Nov 26, 2025  
**Status**: ✅ PRODUCTION
