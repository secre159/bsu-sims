# SIMS Validation Requirements Analysis

## Executive Summary
The system currently has basic validation but **MISSING critical business logic** for real-world operations. This document defines all validation rules that must be enforced.

---

## 1. STUDENT STATUS WORKFLOW VALIDATION

### Current Issue
❌ No status validation - "On Leave" students can still enroll in courses
❌ No status transition rules
❌ No status change date tracking

### Real-World Requirements

| Scenario | Current Behavior | Should Be |
|----------|------------------|-----------|
| Active student enrolls | ✅ Allowed | ✅ Allowed |
| On Leave student enrolls | ❌ Allowed | ❌ BLOCKED |
| Graduated student enrolls | ❌ Allowed | ❌ BLOCKED |
| Dropped student enrolls | ❌ Allowed | ❌ BLOCKED |
| Transferred student enrolls | ❌ Allowed | ⚠️ Conditional |

### Implementation Needed
```php
// In StudentSubjectController::enroll()
if ($student->status !== 'Active') {
    return back()->with('error', "Cannot enroll: Student status is {$student->status}");
}
```

---

## 2. CREDIT HOUR VALIDATION

### Current Issue
❌ No maximum units per semester check
❌ No minimum units for full-time status
❌ Students can exceed reasonable course load

### Real-World Requirements (Philippine Standards)
- **Maximum**: 18 units per semester (standard)
- **Minimum**: 12 units for full-time status
- **Exception**: Students on probation may be limited to 12-15 units

### Calculation Logic
```php
// Get current semester enrollments
$currentEnrollments = $student->enrollments()
    ->where('academic_year_id', $currentYear->id)
    ->with('subject')
    ->get();

$totalUnits = $currentEnrollments->sum(function($e) {
    return $e->subject->units ?? 0;
});

// Validation
$newSubjectUnits = $subject->units ?? 0;
if ($totalUnits + $newSubjectUnits > 18) {
    return back()->with('error', "Cannot enroll: Would exceed 18 units. Current: {$totalUnits}, New: {$newSubjectUnits}");
}
```

---

## 3. PREREQUISITE VALIDATION

### Current Issue
⚠️ Partial - Prerequisites stored but verification incomplete
❌ No passing grade validation (60+ requirement)
❌ No grade point checking

### Real-World Requirements
- Student MUST complete prerequisite with **passing grade (60+)**
- Cannot register for course if:
  - Prerequisite not attempted
  - Prerequisite not passed (grade < 60)
  - Prerequisite in progress (not yet completed)

### Implementation Fix
```php
// Current implementation skips grade check
public function hasMetPrerequisites($student)
{
    foreach ($this->prerequisite_subject_ids as $prereqId) {
        $completion = $student->enrollments()
            ->where('subject_id', $prereqId)
            ->where('status', 'Completed')
            ->first();
        
        // MISSING: Check if grade >= 60
        if (!$completion || $completion->grade < 60) {
            return false; // ← ADD THIS
        }
    }
    return true;
}
```

---

## 4. YEAR LEVEL VALIDATION

### Current Issue
❌ No year level matching - 1st Year student could take 4th Year course
❌ No progression validation

### Real-World Requirements
- Student can only enroll in courses for their **year level or lower**
- Progressive enrollment: Must complete lower levels before advancing
- 1st Year student taking 3rd Year course = ERROR

### Implementation
```php
public function enroll(Request $request, Student $student)
{
    $subject = Subject::findOrFail($validated['subject_id']);
    
    // MISSING VALIDATION
    $studentYearOrder = ['1st Year' => 1, '2nd Year' => 2, '3rd Year' => 3, '4th Year' => 4, '5th Year' => 5];
    $studentLevel = $studentYearOrder[$student->year_level];
    $subjectLevel = $studentYearOrder[$subject->year_level];
    
    if ($subjectLevel > $studentLevel) {
        return back()->with('error', "Cannot enroll: {$subject->name} is for {$subject->year_level} students only");
    }
}
```

---

## 5. PROGRAM MATCHING VALIDATION

### Current Issue
❌ No validation - Student from IT can take Criminology courses
❌ No program-specific course restrictions

### Real-World Requirements
- Student can ONLY enroll in courses from their **assigned program**
- Cannot take electives from other programs (without explicit approval)

### Implementation
```php
public function enroll(Request $request, Student $student)
{
    $subject = Subject::findOrFail($validated['subject_id']);
    
    // MISSING VALIDATION
    if ($subject->program_id !== $student->program_id) {
        return back()->with('error', "Cannot enroll: {$subject->name} is not offered in {$student->program->code}");
    }
}
```

---

## 6. DUPLICATE ENROLLMENT VALIDATION

### Current Issue
⚠️ Partial - Checks for same academic year, but missing:
  - Same semester check
  - Grade replacement logic

### Real-World Requirements
- Student cannot take same subject twice in same semester
- Can retake subject in different semester if failed
- Only latest grade counts for GPA

### Current Code
```php
// Existing check - GOOD
$exists = Enrollment::where('student_id', $student->id)
    ->where('subject_id', $validated['subject_id'])
    ->where('academic_year_id', $currentAcademicYear?->id)
    ->exists();
```

### Enhancement Needed
```php
// Check for passing grade in any academic year
$previousPassing = Enrollment::where('student_id', $student->id)
    ->where('subject_id', $validated['subject_id'])
    ->where('status', 'Completed')
    ->where('grade', '>=', 60)
    ->exists();

if ($previousPassing) {
    return back()->with('error', 'Student already passed this subject. Retaking not allowed.');
}
```

---

## 7. REGISTRATION PERIOD VALIDATION

### Current Issue
❌ MISSING - Students can enroll anytime
❌ No registration deadline enforcement

### Real-World Requirements
- Enrollment ONLY allowed during **registration period**
- Dates: registration_start_date to registration_end_date
- After deadline: manual approval from Registrar required

### Implementation
```php
public function enroll(Request $request, Student $student)
{
    $currentAcademicYear = AcademicYear::where('is_current', true)->first();
    $today = now()->toDateString();
    
    // MISSING VALIDATION
    if ($today < $currentAcademicYear->registration_start_date->toDateString()) {
        return back()->with('error', 'Registration not yet open');
    }
    if ($today > $currentAcademicYear->registration_end_date->toDateString()) {
        return back()->with('error', 'Registration period has closed');
    }
}
```

---

## 8. ADD/DROP DEADLINE VALIDATION

### Current Issue
❌ MISSING - Students can drop anytime

### Real-World Requirements
- Students can add courses during add/drop period
- After add_drop_deadline: Cannot drop without "W" (Withdrawal) grade
- Withdrawal affects transcript

### Implementation
```php
public function drop(Student $student, Enrollment $enrollment)
{
    $today = now()->toDateString();
    $academicYear = $enrollment->academicYear;
    
    // MISSING VALIDATION
    if ($today <= $academicYear->add_drop_deadline->toDateString()) {
        $enrollment->delete(); // Hard delete is OK
    } else {
        // Change status to "Withdrawn" instead of deleting
        $enrollment->update([
            'status' => 'Withdrawn',
            'grade' => 'W',
            'remarks' => 'Withdrawn after add/drop deadline'
        ]);
    }
}
```

---

## 9. ENROLLMENT STATUS FLOW VALIDATION

### Current Issue
❌ MISSING - No status flow validation
❌ Can move from Completed → Enrolled (invalid)

### Real-World Requirements
Valid transitions:
- Enrolled → Completed (grades entered)
- Enrolled → Dropped (during add/drop)
- Enrolled → Withdrawn (after add/drop)
- Completed → (terminal - no changes)
- Dropped → (terminal - no changes)

### Implementation
```php
public function updateGrade(Enrollment $enrollment, $newGrade)
{
    // Prevent changing completed grades
    if ($enrollment->status === 'Completed' || $enrollment->status === 'Dropped') {
        return back()->with('error', 'Cannot modify finalized enrollment');
    }
    
    // Only update while Enrolled
    if ($enrollment->status !== 'Enrolled') {
        return back()->with('error', 'Invalid enrollment status');
    }
}
```

---

## 10. CAPACITY/SECTION VALIDATION

### Current Issue
❌ MISSING - No section or room capacity checks
❌ No waiting list management

### Real-World Requirements
- Each subject may have multiple sections (A, B, C)
- Each section has **maximum capacity** (30-50 students)
- Cannot exceed capacity: student added to **waiting list**
- No sections = No enrollments possible

### Implementation (Future - Phase 2)
```php
// When Section model is added:
public function enroll(Request $request, Student $student)
{
    $section = Section::findOrFail($request->section_id);
    
    if ($section->current_enrollment >= $section->capacity) {
        // Add to waiting list
        WaitingList::create([
            'student_id' => $student->id,
            'section_id' => $section->id,
        ]);
        return back()->with('info', 'Section full. Added to waiting list.');
    }
}
```

---

## 11. ACADEMIC STANDING VALIDATION

### Current Issue
❌ MISSING - No academic probation checks
❌ Cannot limit course load for students on probation

### Real-World Requirements
- **Good Standing**: GPA >= 2.0, no failed subjects
- **Probation**: GPA < 2.0 or 3+ failed subjects
- **Probation Limit**: Max 12 units (not 18)
- **Suspension**: 2+ consecutive probations

### Implementation (Future - Phase 2)
```php
public function enroll(Request $request, Student $student)
{
    $academicStanding = $student->calculateAcademicStanding();
    
    if ($academicStanding === 'Probation') {
        $maxUnits = 12; // Limited load
        if ($totalUnits + $newUnits > 12) {
            return back()->with('error', 'Probation students limited to 12 units');
        }
    }
}
```

---

## 12. SUBJECT ACTIVE/INACTIVE VALIDATION

### Current Issue
⚠️ Partial - Checks `is_active` in index view but:
  - ❌ No validation in enroll() method
  - ❌ Can enroll in inactive subjects

### Real-World Requirements
- Cannot enroll in subject marked `is_active = false`
- Prevents enrollment in cancelled/discontinued courses

### Implementation
```php
public function enroll(Request $request, Student $student)
{
    $subject = Subject::findOrFail($validated['subject_id']);
    
    // MISSING VALIDATION
    if (!$subject->is_active) {
        return back()->with('error', "{$subject->name} is no longer offered");
    }
}
```

---

## 13. CONCURRENT ENROLLMENT VALIDATION

### Current Issue
❌ MISSING - No time conflict detection
❌ Students can have overlapping class times

### Real-World Requirements (When sections added)
- Student cannot take 2 courses with **same meeting times**
- Example: Cannot take CS101 (MWF 8-9am) AND MATH101 (MWF 8-9am)
- Summer/Online courses: No time conflicts with other courses

### Implementation (Future)
```php
public function hasScheduleConflict($student, $newSection)
{
    $existingEnrollments = $student->enrollments()
        ->whereHas('subject.sections')
        ->get();
    
    foreach ($existingEnrollments as $enrollment) {
        if ($this->timesOverlap($newSection, $enrollment->section)) {
            return true;
        }
    }
    return false;
}
```

---

## Implementation Priority

### CRITICAL (Deploy immediately)
1. ✅ Status validation (Active only)
2. ✅ Credit hour validation (12-18 units)
3. ✅ Prerequisites with grade check (60+)
4. ✅ Year level validation
5. ✅ Program matching
6. ✅ Duplicate enrollment with retake logic
7. ✅ Subject active/inactive check

### HIGH PRIORITY (This week)
8. ⚠️ Registration period validation
9. ⚠️ Add/drop deadline validation
10. ⚠️ Enrollment status flow

### MEDIUM PRIORITY (Phase 2)
11. Academic standing (GPA-based)
12. Capacity/section validation
13. Schedule conflict detection

---

## Testing Checklist

### Test Cases for Each Validation

#### Status Validation
```
[ ] ✅ Active student can enroll
[ ] ❌ On Leave student blocked
[ ] ❌ Graduated student blocked
[ ] ❌ Dropped student blocked
```

#### Credit Hours
```
[ ] ✅ 4 units + 12 units = 16 units (OK)
[ ] ❌ 10 units + 10 units = 20 units (exceeds 18 max)
```

#### Prerequisites
```
[ ] ✅ Student passed PROG101 can take PROG102
[ ] ❌ Student with grade 55 in PROG101 blocked
[ ] ❌ Student without PROG101 blocked
```

#### Year Level
```
[ ] ✅ 2nd Year student can take 1st or 2nd Year courses
[ ] ❌ 1st Year student blocked from 3rd Year course
```

#### Program Matching
```
[ ] ✅ BSIT student can take CS101 (BSIT course)
[ ] ❌ BSIT student blocked from CRIM101 (BSCrim course)
```

#### Duplicate Enrollment
```
[ ] ✅ First time taking PROG101 = OK
[ ] ❌ Taking PROG101 again in same semester = ERROR
[ ] ❌ Retaking after passing PROG101 = ERROR
```

---

## Real-World Workflow Verification

### Scenario 1: Normal Registration ✅
```
Maria (BSIT, 2nd Year, Active, GPA 3.2)
→ Enroll in: PROG102 (3 units), WEB101 (3 units), OOP201 (3 units)
→ Total: 9 units (OK)
→ Result: SUCCESS
```

### Scenario 2: Blocked: On Leave ❌
```
Juan (BTVTEd, 4th Year, On Leave)
→ Try to enroll in: BTVTEd100
→ Result: ERROR "Student status is On Leave"
```

### Scenario 3: Blocked: Credit Overload ❌
```
Ana (BSEd, 3rd Year, Active)
→ Already enrolled in: 5 × 3-unit courses = 15 units
→ Try to add: 4 × 3-unit course = would be 18 units
→ Try to add: 5th × 3-unit course = would be 21 units
→ Result: ERROR "Would exceed 18 units"
```

### Scenario 4: Blocked: Prerequisite Failed ❌
```
Jose (BSIT, 2nd Year)
→ Grade in PROG101: 55 (FAILED)
→ Try to enroll in: PROG102
→ Result: ERROR "Must pass PROG101 with 60+ grade"
```

### Scenario 5: Blocked: Outside Registration Period ❌
```
Current Date: March 15 (after registration end Feb 28)
Student tries to enroll
→ Result: ERROR "Registration period closed"
```

---

## Current System Status

| Validation | Status | Impact |
|-----------|--------|--------|
| Status | ❌ MISSING | CRITICAL |
| Credit Hours | ❌ MISSING | CRITICAL |
| Prerequisites (grade) | ⚠️ PARTIAL | HIGH |
| Year Level | ❌ MISSING | HIGH |
| Program Matching | ❌ MISSING | HIGH |
| Duplicate Enrollment | ⚠️ PARTIAL | MEDIUM |
| Registration Period | ❌ MISSING | HIGH |
| Add/Drop Deadline | ❌ MISSING | MEDIUM |
| Subject Active | ⚠️ PARTIAL | MEDIUM |

**System Readiness for Production**: ⚠️ **30%** (too many critical validations missing)
**Estimated Fix Time**: 4-6 hours for critical validations
