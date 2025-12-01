# Prerequisite System - Complete Flow & Usage Guide

## System Overview

The prerequisite system ensures students only take courses they're ready for, based on having completed prior courses. It's a 3-actor system:

```
ADMIN (creates courses)  →  DATABASE (stores prerequisites)  →  STUDENT (tries to enroll)
```

---

## PHASE 1: ADMIN SETUP (Creating Prerequisites)

### Step 1: Admin Creates Foundation Courses

**Scenario:** BSU-Sokod Computer Science program needs 4 courses setup.

```
Admin goes to: Admin Dashboard → Subjects → Add New Subject

COURSE 1: "Introduction to Programming"
├─ Code: CS101
├─ Name: Introduction to Programming
├─ Year Level: 1st Year
├─ Semester: 1st Semester
├─ Units: 3
├─ Prerequisites: [NONE] ← Foundation course
└─ Active: ✓

COURSE 2: "Data Structures"
├─ Code: CS102
├─ Name: Data Structures
├─ Year Level: 1st Year
├─ Semester: 2nd Semester
├─ Units: 3
├─ Prerequisites: [NONE] ← Foundation course
└─ Active: ✓

COURSE 3: "Advanced Programming"
├─ Code: CS201
├─ Name: Advanced Programming
├─ Year Level: 2nd Year
├─ Semester: 1st Semester
├─ Units: 3
├─ Prerequisites: [CS101] ← REQUIRES: Intro to Programming
└─ Active: ✓

COURSE 4: "Database Design"
├─ Code: CS202
├─ Name: Database Design
├─ Year Level: 2nd Year
├─ Semester: 1st Semester
├─ Units: 3
├─ Prerequisites: [CS101, CS102] ← REQUIRES: Intro + Data Structures
└─ Active: ✓
```

### Step 2: Admin Sets Prerequisites for CS201

**Action:** Edit CS201 → Scroll down to "Prerequisites" section

```
Prerequisites Section:
┌─────────────────────────────────────────────┐
│ Select subjects that must be completed      │
│ before taking this course                   │
├─────────────────────────────────────────────┤
│ ☐ CS101 - Introduction to Programming      │
│   (1st Year) ← CHECK THIS BOX              │
├─────────────────────────────────────────────┤
│ ☐ CS102 - Data Structures                  │
│   (1st Year)                               │
├─────────────────────────────────────────────┤
│ Database is updated:                        │
│ prerequisite_subject_ids = [1]              │
│ (where 1 = CS101 ID)                        │
└─────────────────────────────────────────────┘
```

### Step 3: Admin Sets Prerequisites for CS202

**Action:** Edit CS202 → Prerequisites section → Check BOTH CS101 and CS102

```
Prerequisites Section:
┌─────────────────────────────────────────────┐
│ ☑ CS101 - Introduction to Programming      │
│   (1st Year) ← CHECKED                     │
├─────────────────────────────────────────────┤
│ ☑ CS102 - Data Structures                  │
│   (1st Year) ← CHECKED                     │
└─────────────────────────────────────────────┘

Database is updated:
prerequisite_subject_ids = [1, 2]
(CS101 AND CS102 both required)
```

**Database State After Admin Setup:**

```sql
SELECT id, code, name, prerequisite_subject_ids FROM subjects;

| id | code | name                    | prerequisite_subject_ids |
|----+------+------------------------+--------------------------|
| 1  | CS101| Intro to Programming    | [] (no prerequisites)    |
| 2  | CS102| Data Structures         | [] (no prerequisites)    |
| 3  | CS201| Advanced Programming    | [1] (requires CS101)     |
| 4  | CS202| Database Design         | [1,2] (requires CS101+2) |
```

---

## PHASE 2: STUDENT ENROLLMENT ATTEMPT

### Student Profile
```
Student: John Doe
├─ Student ID: 2024-001
├─ Program: Computer Science
├─ Year Level: 1st Year
├─ Status: Active
└─ Current Enrollments: NONE
```

### Scenario A: Student Tries to Take Advanced Course (WITHOUT Prerequisites)

**What Happens:**

```
1. Student logs in → Student Dashboard → My Subjects
   ↓
2. Clicks "Enroll in Subject" → Sees available courses
   ↓
3. Sees "CS201 - Advanced Programming" (2nd Year, 3 units)
   ↓
4. Clicks "Enroll" button
   ↓
5. SYSTEM STARTS VALIDATION (8 checks happen):
   
   Check 1: Is student status = "Active"? ✅ YES
   Check 2: Is subject is_active = true? ✅ YES
   Check 3: Same program? ✅ YES (both CS)
   Check 4: Below year level? ✅ YES (1st year can take 2nd year? Wait...)
   
   ⚠️ Actually Check 4 says "Cannot take courses ABOVE year level"
       1st Year student = level 1
       CS201 is 2nd Year = level 2
       Level 2 > Level 1? YES!
       ❌ BLOCKED: "Cannot enroll: CS201 is for 2nd Year students"
```

**Result:** ❌ Enrollment FAILED (blocked by year-level check before prerequisites even matter)

### Scenario B: 2nd Year Student Tries Advanced Course (WITHOUT Prerequisites)

**What Happens:**

```
Student: Jane Doe
├─ Year Level: 2nd Year ← NOW 2nd Year!
├─ Current Enrollments: NONE
└─ Status: Active

1. Jane tries to enroll in CS201
   ↓
2. SYSTEM VALIDATION:
   
   Check 1: Is student status = "Active"? ✅ YES
   Check 2: Is subject is_active = true? ✅ YES
   Check 3: Same program? ✅ YES
   Check 4: Below/equal year level? ✅ YES (2nd year can take 2nd year)
   
   Check 5: ⚠️ PREREQUISITE CHECK ← THIS IS THE KEY!
   
   CS201 requires: prerequisite_subject_ids = [1] (CS101)
   
   Does Jane have completed CS101?
   
   Query: SELECT * FROM enrollments 
          WHERE student_id = Jane AND 
                subject_id = 1 AND 
                status = 'Completed'
   
   Result: ❌ NO ENROLLMENT FOUND
   
   ❌ BLOCKED: "Cannot enroll: Must complete the following 
      prerequisite course(s): CS101 - Introduction to Programming"
```

**Result:** ❌ Enrollment FAILED (blocked by prerequisite check)

---

## PHASE 3: STUDENT TAKES PREREQUISITE

### Step 1: Jane First Enrolls in CS101

```
Jane goes to courses → Selects CS101 → Enroll
↓
VALIDATION:
  Check 1-4: All pass ✅
  Check 5: CS101 has NO prerequisites ✅
  Check 6-8: All pass ✅
↓
✅ ENROLLMENT SUCCESSFUL

Database update:
INSERT INTO enrollments (student_id, subject_id, academic_year_id, status)
VALUES (Jane_id, 1, current_year, 'Enrolled');
```

### Step 2: Jane's Enrollment Shows as "Enrolled"

```
Jane's Dashboard:
┌──────────────────────────────────────────────┐
│ MY ENROLLED SUBJECTS (2024-2025-1st Sem)    │
├──────────────────────────────────────────────┤
│ CS101 - Introduction to Programming         │
│ Status: Enrolled ← Currently taking it       │
│ Units: 3                                    │
│ Action: [Drop Course]                       │
└──────────────────────────────────────────────┘
```

### Step 3: Semester Ends → Grade Submitted → Status Changes

**Note: This requires future grading system**

```
When grading system is implemented:

1. Professor enters Jane's grade for CS101: A (4.0)
   ↓
2. System automatically updates enrollment:
   UPDATE enrollments 
   SET status = 'Completed', grade = 4.0
   WHERE student_id = Jane AND subject_id = 1
   ↓
3. Jane's enrollment now shows:
   CS101 - Introduction to Programming
   Status: Completed ✅
   Grade: A (4.0)
```

**For now (without grading):** Enrollment stays as "Enrolled" indefinitely
- Admin can manually update if needed:
  ```sql
  UPDATE enrollments SET status = 'Completed' 
  WHERE student_id = Jane_id AND subject_id = 1;
  ```

---

## PHASE 4: STUDENT RETRIES ADVANCED COURSE

### Now Jane Can Enroll in CS201

```
Jane tries to enroll in CS201 again
↓
SYSTEM VALIDATION:

Check 1-4: All pass ✅
Check 5: PREREQUISITE CHECK
   
   CS201 requires: [1] (CS101)
   
   Query: SELECT * FROM enrollments 
          WHERE student_id = Jane AND 
                subject_id = 1 AND 
                status = 'Completed'
   
   Result: ✅ FOUND!
   
   Jane has completed CS101 ✓
↓
Check 6-8: All pass ✅
↓
✅ ENROLLMENT SUCCESSFUL
```

**Result:** ✅ Jane is now enrolled in CS201

### Jane's Dashboard Now Shows:

```
MY ENROLLED SUBJECTS (2024-2025-1st Sem)
┌──────────────────────────────────────────────┐
│ CS101 - Introduction to Programming         │
│ Status: Completed ✅                         │
│ Grade: A                                    │
├──────────────────────────────────────────────┤
│ CS201 - Advanced Programming                │
│ Status: Enrolled (taking now)               │
│ Units: 3                                    │
│ Action: [Drop Course]                       │
└──────────────────────────────────────────────┘
```

---

## PHASE 5: MULTI-PREREQUISITE EXAMPLE

### Scenario: CS202 Requires BOTH CS101 AND CS102

```
CS202 Database Design prerequisites: [1, 2]
├─ Requires CS101 (Intro) 
└─ Requires CS102 (Data Structures)

Bob (2nd Year student) tries to enroll in CS202:

Current status:
├─ CS101: Completed ✅
├─ CS102: Enrolled (still taking)
└─ CS202: TRYING TO ENROLL

Check 5: PREREQUISITE CHECK
   
   For CS101: Does Bob have status='Completed'? 
   ✅ YES
   
   For CS102: Does Bob have status='Completed'? 
   ❌ NO (status='Enrolled', not 'Completed')
   
   Missing prerequisites: CS102
   
   ❌ BLOCKED: "Cannot enroll: Must complete the following 
      prerequisite course(s): CS102 - Data Structures"
```

**Result:** ❌ Bob must finish CS102 first

### After Bob Completes CS102:

```
Bob's enrollments:
├─ CS101: Completed ✅
├─ CS102: Completed ✅ ← Now completed!
└─ CS202: TRYING TO ENROLL (again)

Check 5: PREREQUISITE CHECK
   
   For CS101: Completed? ✅ YES
   For CS102: Completed? ✅ YES
   
   All prerequisites satisfied!
   
   ✅ ENROLLMENT SUCCESSFUL
```

---

## COMPLETE DATA FLOW DIAGRAM

```
┌─────────────────────────────────────────────────────────────────┐
│                         ADMIN SETUP                             │
└─────────────────────────────────────────────────────────────────┘
                              ↓
    ┌──────────────────────────────────────────────────────────┐
    │ 1. Create subjects (CS101, CS102, CS201, CS202)         │
    │ 2. Edit CS201: Add CS101 to prerequisites               │
    │ 3. Edit CS202: Add CS101 + CS102 to prerequisites       │
    │ 4. Save to database                                      │
    └──────────────────────────────────────────────────────────┘
                              ↓
    ┌──────────────────────────────────────────────────────────┐
    │ subjects table:                                          │
    │ id=1, CS101, prerequisites=[]                           │
    │ id=2, CS102, prerequisites=[]                           │
    │ id=3, CS201, prerequisites=[1]                          │
    │ id=4, CS202, prerequisites=[1,2]                        │
    └──────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                      STUDENT ENROLLMENT                         │
└─────────────────────────────────────────────────────────────────┘
                              ↓
    ┌──────────────────────────────────────────────────────────┐
    │ Student selects course to enroll                        │
    └──────────────────────────────────────────────────────────┘
                              ↓
    ┌──────────────────────────────────────────────────────────┐
    │ 8-POINT VALIDATION CHECK:                              │
    │ 1. Student status = Active? ✓                          │
    │ 2. Subject is_active? ✓                                │
    │ 3. Same program? ✓                                     │
    │ 4. Year level OK? ✓                                    │
    │ 5. PREREQUISITES MET? ← KEY POINT                      │
    │ 6. No duplicate? ✓                                     │
    │ 7. Credit hours OK? ✓                                  │
    │ 8. Registration period? ✓                              │
    └──────────────────────────────────────────────────────────┘
                              ↓
    ┌──────────────────────────────────────────────────────────┐
    │ IF ANY CHECK FAILS:                                     │
    │ ❌ Show error message                                   │
    │ Enrollment blocked                                     │
    │ Student sees: "Cannot enroll: [specific reason]"       │
    └──────────────────────────────────────────────────────────┘
                      OR (if all pass)
    ┌──────────────────────────────────────────────────────────┐
    │ IF ALL CHECKS PASS:                                     │
    │ ✅ Create enrollment record                             │
    │ INSERT INTO enrollments:                                │
    │ (student_id, subject_id, academic_year_id, status)     │
    │ VALUES (Bob, CS201, 2024-2025-1, 'Enrolled')           │
    └──────────────────────────────────────────────────────────┘
                              ↓
    ┌──────────────────────────────────────────────────────────┐
    │ Student Dashboard Updated:                              │
    │ "CS201 - Advanced Programming (Enrolled)"               │
    └──────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                    SEMESTER ENDS                                │
└─────────────────────────────────────────────────────────────────┘
                              ↓
    ┌──────────────────────────────────────────────────────────┐
    │ (FUTURE) Grading system:                               │
    │ Professor enters grades                                │
    │ System updates enrollment status to 'Completed'        │
    └──────────────────────────────────────────────────────────┘
                              ↓
    ┌──────────────────────────────────────────────────────────┐
    │ enrollments table:                                      │
    │ student_id=Bob, subject_id=1, status='Completed'       │
    │ student_id=Bob, subject_id=3, status='Enrolled'        │
    │ (CS101 completed, CS201 still enrolled)               │
    └──────────────────────────────────────────────────────────┘
```

---

## QUICK REFERENCE: State Transitions

### Enrollment States

```
┌─────────────────────────────────────────────────────────────┐
│                    ENROLLMENT LIFECYCLE                     │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  [Student Enrolls]                                         │
│           ↓                                                │
│  Enrollment Status = 'Enrolled' ← Only current option      │
│           ↓                                                │
│  Semester Progresses...                                    │
│           ↓                                                │
│  [FUTURE: Grades Submitted]                               │
│           ↓                                                │
│  Status Changes to:                                        │
│  - 'Completed' (if passed) ← Can now take prerequisites   │
│  - 'Failed' (if not passed)                               │
│  - 'Dropped' (if withdrawn)                               │
│           ↓                                                │
│  Student can now enroll in courses requiring this as prep │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## EXAMPLE: Real-World Course Progression

### Computer Science Program Structure

```
SEMESTER 1 (1st Year):
├─ CS101: Introduction to Programming ← Foundation
├─ CS102: Data Structures (no prerequisites)
├─ MATH101: Calculus I
└─ ENG101: English Composition

SEMESTER 2 (1st Year):
├─ CS103: Web Development (requires CS101)
└─ MATH102: Calculus II

SEMESTER 3 (2nd Year):
├─ CS201: Advanced Programming (requires CS101) ← Bob can take
├─ CS202: Database Design (requires CS101 + CS102) ← Bob can take
├─ CS203: Algorithms (requires CS101 + CS102)
└─ MATH201: Discrete Math

SEMESTER 4 (2nd Year):
├─ CS301: Software Engineering (requires CS201 + CS202)
├─ CS302: AI Basics (requires CS201)
└─ CS303: Networks (requires CS202)
```

### Bob's Actual Progression

```
Year 1, Semester 1:
✅ Enrolls in: CS101, CS102, MATH101, ENG101
Status: All "Enrolled"

Year 1, Semester 2:
✅ Completes: CS101, CS102, MATH101, ENG101 → "Completed"
✅ Can now enroll in: CS103 (requires CS101) → Success!
❌ Cannot enroll in: CS201 (requires CS101 + year level) → Wrong year

Year 2, Semester 1:
✅ Completes: CS103
✅ Now 2nd Year student
✅ Enrolls in: CS201 (has CS101 ✓), Success!
✅ Enrolls in: CS202 (has CS101 ✓ and CS102 ✓), Success!
✅ Enrolls in: CS203 (has CS101 ✓ and CS102 ✓), Success!

Year 2, Semester 2:
✅ Completes: CS201, CS202
✅ Enrolls in: CS301 (requires CS201 ✓ and CS202 ✓), Success!
✅ Can enroll in: CS302 (requires CS201 ✓), Success!
```

---

## Common Error Messages & Solutions

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "Cannot enroll: Must complete CS101..." | Missing prerequisite | Take and complete CS101 first |
| "Cannot enroll: CS201 is for 2nd Year students" | Year level too low | Advance to 2nd Year, then enroll |
| "Cannot enroll: Student status is 'Dropped'" | Not Active | Change status back to Active |
| "Cannot enroll: Duplicate enrollment" | Already enrolled | Drop previous enrollment first |
| "Cannot enroll: Would exceed max 18 units" | Too many courses | Drop a course or wait next semester |

---

## Key Points to Remember

1. **Prerequisites are COMPLETION-based, not GRADE-based**
   - Currently: Just need status = 'Completed'
   - Future: Can add "grade >= C" requirement

2. **Prerequisites use AND logic, not OR logic**
   - Must complete ALL prerequisites
   - Not "A or B", it's "A and B"

3. **Prerequisites block enrollment at the point of student action**
   - Admin can change prerequisites anytime
   - New requirements apply to future enrollments
   - Existing enrollments not affected

4. **System checks prerequisites in order**
   - If any prerequisite fails, enrollment blocked immediately
   - Error message lists ALL missing prerequisites

5. **Status = 'Completed' is the trigger**
   - Until grading system adds way to mark as 'Completed'
   - Prerequisites effectively block everyone
   - Admin can manually update database for testing
