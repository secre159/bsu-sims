# BSU-Sokod SIMS - Complete System Guide from Start to Finish

## What is SIMS?

**SIMS = Student Information Management System**

It's a web application that manages everything about students, courses, and enrollments at BSU-Sokod. Think of it as a digital version of a university registrar's office.

---

## PART 1: THE BIG PICTURE

### What Does SIMS Do?

```
┌─────────────────────────────────────────────────────────────┐
│                    SIMS SYSTEM                              │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  INPUT (Admin does work)                                   │
│  ├─ Create programs (Computer Science, Business, etc.)    │
│  ├─ Create courses (CS101, CS201, etc.)                   │
│  ├─ Create students (John Doe, Jane Smith, etc.)          │
│  └─ Set academic calendar (when registration opens, etc.) │
│                                                             │
│         ↓↓↓ (System processes) ↓↓↓                        │
│                                                             │
│  LOGIC (System enforces rules)                            │
│  ├─ Prevent students from taking courses above year level │
│  ├─ Prevent students from enrolling twice in same course  │
│  ├─ Enforce prerequisite requirements                     │
│  └─ Limit courses to 18 units per semester                │
│                                                             │
│         ↓↓↓ (Results) ↓↓↓                                 │
│                                                             │
│  OUTPUT (Everyone sees results)                           │
│  ├─ Students see their enrolled courses                   │
│  ├─ Admin sees reports                                    │
│  ├─ Faculty sees their student lists                      │
│  └─ Registrar manages everything                          │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## PART 2: THE DATA (What Information Is Stored?)

### The Five Core "Things" in SIMS

```
1. PROGRAMS
   └─ Computer Science
   └─ Business Administration
   └─ Engineering

2. SUBJECTS (Courses)
   └─ CS101: Introduction to Programming
   └─ CS201: Advanced Programming
   └─ BUS101: Accounting Basics

3. STUDENTS
   └─ John Doe (2024-001)
   └─ Jane Smith (2024-002)
   └─ Bob Johnson (2024-003)

4. ACADEMIC YEARS
   └─ 2024-2025 (1st Semester)
   └─ 2024-2025 (2nd Semester)

5. ENROLLMENTS (Connections)
   └─ John enrolled in CS101
   └─ Jane enrolled in CS201
```

### How They're Related

```
PROGRAM
  ↓
  ├─→ SUBJECTS (courses in that program)
  │     └─→ ENROLLMENTS (students taking that course)
  │           └─→ STUDENT
  │
STUDENT
  ├─→ PROGRAM (what program they're in)
  ├─→ ACADEMIC YEAR (current year)
  └─→ ENROLLMENTS (what courses they're taking)
```

---

## PART 3: STEP-BY-STEP WALKTHROUGH

### Scenario: Setting Up Computer Science Program for 2024-2025

---

## STEP 1: ADMIN LOGS IN

**What happens:**
1. Admin goes to: `http://localhost:8000/login`
2. Enters: `admin@bsu-sokod.edu.ph` / `password`
3. Lands on Dashboard

```
Dashboard shows:
├─ Students: 50
├─ Programs: 10
├─ Subjects: 52
├─ Enrollments: 237
└─ Quick Actions: [Manage Students] [Manage Subjects] [Reports]
```

---

## STEP 2: ADMIN CREATES ACADEMIC CALENDAR

**Goal:** Set up when registration opens/closes, when classes start, etc.

**What admin does:**
1. Click: **Admin Dashboard** → **Academic Years**
2. Click: **Add New Academic Year**
3. Fill out form:

```
Form Fields:
├─ Year Code: "2024-2025"
├─ Semester: "1st Semester"
├─ Start Date: 2025-01-15
├─ End Date: 2025-05-31
├─ Registration Start: 2024-12-01
├─ Registration End: 2024-12-30
├─ Add/Drop Deadline: 2025-02-01
├─ Classes Start: 2025-01-20
├─ Classes End: 2025-05-25
├─ Midterm Period: 2025-03-01 to 2025-03-14
├─ Exam Period: 2025-05-26 to 2025-06-15
└─ Mark as Current: ✓ (This is the active semester)
```

**Result in Database:**

```sql
INSERT INTO academic_years (
  year_code, semester, start_date, end_date, 
  registration_start_date, registration_end_date,
  add_drop_deadline, classes_start_date, classes_end_date,
  midterm_start_date, midterm_end_date,
  exam_start_date, exam_end_date, is_current
)
VALUES (
  '2024-2025', '1st Semester', '2025-01-15', '2025-05-31',
  '2024-12-01', '2024-12-30',
  '2025-02-01', '2025-01-20', '2025-05-25',
  '2025-03-01', '2025-03-14',
  '2025-05-26', '2025-06-15', true
)
```

**Why this matters:**
- Students can ONLY enroll between Dec 1 - Dec 30
- After Feb 1, students can't drop courses
- This is the "current" semester students see

---

## STEP 3: ADMIN CREATES A PROGRAM

**Goal:** Define what Computer Science program is

**What admin does:**
1. Click: **Manage** → **Programs**
2. Click: **Add New Program**
3. Fill out form:

```
Form Fields:
├─ Program Code: "CS"
├─ Program Name: "Bachelor of Science in Computer Science"
├─ Description: "A 4-year program focusing on..."
├─ Degree Level: "Bachelor's Degree"
├─ Duration: "4 Years"
├─ Active: ✓ (Yes, this program is active)
└─ [Add Program Button]
```

**What Admin Filled Out:**
- Code: CS
- Name: Bachelor of Science in Computer Science
- Years: 4 years (1st Year → 4th Year)

**Result in Database:**

```sql
INSERT INTO programs (code, name, description, is_active)
VALUES (
  'CS',
  'Bachelor of Science in Computer Science',
  'A 4-year program focusing on...',
  true
)
```

**Important:** The system now knows:
- There's a CS program
- It's active (students can enroll)
- It lasts 4 years
- Students progress: 1st Year → 2nd Year → 3rd Year → 4th Year

---

## STEP 4: ADMIN CREATES COURSES

**Goal:** Define what courses students take in CS program

**What admin does:**
1. Click: **Manage** → **Subjects**
2. Click: **Add New Subject**
3. Fill out form for FIRST course:

```
COURSE 1: Introduction to Programming

Form Fields:
├─ Subject Code: "CS101"
├─ Subject Name: "Introduction to Programming"
├─ Description: "Learn basics of programming..."
├─ Units/Credits: 3
├─ Program: "Computer Science" ← Link to CS program
├─ Year Level: "1st Year"
├─ Semester: "1st Semester"
├─ Active: ✓
├─ Prerequisites: [NONE]
└─ [Add Subject Button]
```

**Result in Database:**

```sql
INSERT INTO subjects (
  code, name, units, program_id, year_level, semester, is_active,
  prerequisite_subject_ids
)
VALUES (
  'CS101', 'Introduction to Programming', 3, 1,
  '1st Year', '1st Semester', true, []
)
-- prerequisite_subject_ids = [] means NO prerequisites
```

**Admin repeats for 3 more courses:**

```
COURSE 2:
├─ CS102: Data Structures (1st Year, 2nd Semester)
├─ Prerequisites: [] (none)

COURSE 3:
├─ CS201: Advanced Programming (2nd Year, 1st Semester)
├─ Prerequisites: [1] (requires CS101)

COURSE 4:
├─ CS202: Database Design (2nd Year, 1st Semester)
├─ Prerequisites: [1, 2] (requires CS101 AND CS102)
```

**Database Now Has:**

```
subjects table:
┌────┬────────┬──────────────────────┬───────────────┬──────────┐
│ id │ code   │ name                 │ prerequisites │ year_lvl │
├────┼────────┼──────────────────────┼───────────────┼──────────┤
│ 1  │ CS101  │ Intro Programming    │ []            │ 1st Year │
│ 2  │ CS102  │ Data Structures      │ []            │ 1st Year │
│ 3  │ CS201  │ Advanced Programming │ [1]           │ 2nd Year │
│ 4  │ CS202  │ Database Design      │ [1,2]         │ 2nd Year │
└────┴────────┴──────────────────────┴───────────────┴──────────┘
```

---

## STEP 5: ADMIN CREATES A STUDENT

**Goal:** Add a student to the system

**What admin does:**
1. Click: **Manage** → **Students**
2. Click: **Add New Student**
3. Fill out form:

```
Form Fields:
├─ Student ID: "2024-001"
├─ Last Name: "Doe"
├─ First Name: "John"
├─ Middle Name: "Michael"
├─ Birthdate: 1/15/2006
├─ Gender: "Male"
├─ Contact Number: "09171234567"
├─ Email: "john.doe@bsu-sokod.edu.ph"
├─ Address: "123 Main St, City"
├─ Program: "Computer Science" ← Choose CS program
├─ Year Level: "1st Year" ← Starting as 1st year
├─ Status: "Active" ← Student is active (not dropped/graduated)
├─ Enrollment Date: 2025-01-15
├─ Academic Year: "2024-2025-1st Semester"
└─ [Add Student Button]
```

**Result in Database:**

```sql
INSERT INTO students (
  student_id, last_name, first_name, middle_name, birthdate,
  gender, contact_number, email, address, program_id,
  year_level, status, enrollment_date, academic_year_id
)
VALUES (
  '2024-001', 'Doe', 'John', 'Michael', '2006-01-15',
  'Male', '09171234567', 'john.doe@bsu-sokod.edu.ph', '123 Main St',
  1, '1st Year', 'Active', '2025-01-15', 1
)
```

**Admin repeats for more students:**
- Jane Smith (2024-002) → 1st Year
- Bob Johnson (2024-003) → 2nd Year

**Database Now Has:**

```
students table:
┌─────────────┬──────────┬────────────┬───────────────┬────────────┐
│ student_id  │ name     │ program_id │ year_level    │ status     │
├─────────────┼──────────┼────────────┼───────────────┼────────────┤
│ 2024-001    │ John Doe │ 1 (CS)     │ 1st Year      │ Active     │
│ 2024-002    │ Jane S.  │ 1 (CS)     │ 1st Year      │ Active     │
│ 2024-003    │ Bob J.   │ 1 (CS)     │ 2nd Year      │ Active     │
└─────────────┴──────────┴────────────┴───────────────┴────────────┘
```

---

## PART 4: STUDENT ENROLLMENT (The Main Action)

### NOW IT'S TIME FOR STUDENTS TO REGISTER

---

## STEP 6: STUDENT LOGS IN

**What John (1st Year) does:**
1. Goes to: `http://localhost:8000/login`
2. Enters: `john.doe@student-login.edu.ph` / `password`
3. Lands on Student Dashboard

```
Dashboard shows:
├─ Welcome: "Hello, John Doe"
├─ Program: "Bachelor of Science in Computer Science"
├─ Year Level: "1st Year"
├─ Status: "Active"
├─ Current Enrollment: "None"
└─ Actions: [View Available Courses] [My Subjects] [My Schedule]
```

---

## STEP 7: STUDENT VIEWS AVAILABLE COURSES

**What John does:**
1. Click: **My Subjects** → **Available Courses**
2. System shows courses he can take

```
Available Courses for 1st Year CS Students:

┌─────────────────────────────────────────────────────────┐
│ CS101 - Introduction to Programming                    │
│ Year Level: 1st Year | Semester: 1st Semester | Units: 3│
│ Prerequisites: NONE                                     │
│ [Enroll Button]                                         │
├─────────────────────────────────────────────────────────┤
│ CS102 - Data Structures                                │
│ Year Level: 1st Year | Semester: 2nd Semester | Units: 3│
│ Prerequisites: NONE                                     │
│ [Enroll Button]                                         │
├─────────────────────────────────────────────────────────┤
│ CS201 - Advanced Programming (GRAYED OUT - LOCKED)      │
│ Year Level: 2nd Year | Cannot enroll (not 2nd year)   │
│ [Enroll Button - DISABLED]                              │
└─────────────────────────────────────────────────────────┘
```

**Behind the scenes, system filtered:**
- ✅ Show CS101 (1st Year course) → John is 1st Year
- ✅ Show CS102 (1st Year course) → John is 1st Year
- ❌ Hide CS201 (2nd Year course) → John is 1st Year (year level check)
- ❌ Hide CS202 (2nd Year course) → John is 1st Year

---

## STEP 8: STUDENT ENROLLS IN FIRST COURSE

**What John does:**
1. Click: **[Enroll Button]** for CS101
2. System runs validation checks

```
VALIDATION CHECK #1: Is registration period open?
├─ Today: 2025-01-15
├─ Registration Start: 2024-12-01
├─ Registration End: 2024-12-30
├─ ❌ FAILED: Today is AFTER Dec 30!
└─ Message: "Registration period has closed"

Result: ❌ ENROLLMENT BLOCKED
```

---

## WAIT: It's January 15th but Registration Ended Dec 30th!

**What's happening:**
- Admin needs to have set the registration dates to include Jan 15
- Currently registration is closed
- Students can't enroll

**Admin's mistake:** Should have set registration to close later, like Feb 1

**Admin fixes it:**
1. Click: **Manage** → **Academic Years**
2. Edit **2024-2025-1st Semester**
3. Change: `Registration End: 2024-12-30` → `2025-02-01`
4. Save

---

## STEP 9: STUDENT TRIES AGAIN (After Registration is Extended)

**What John does:**
1. Click: **[Enroll Button]** for CS101 (again)
2. System runs validation checks

```
VALIDATION CHECK #1: Is registration period open?
├─ Today: 2025-01-15
├─ Registration Start: 2024-12-01
├─ Registration End: 2025-02-01
├─ ✅ PASSED: Today is within registration window!

VALIDATION CHECK #2: Is student status = "Active"?
├─ John's status: "Active"
├─ ✅ PASSED

VALIDATION CHECK #3: Is subject active?
├─ CS101 is_active: true
├─ ✅ PASSED

VALIDATION CHECK #4: Same program?
├─ John's program: Computer Science
├─ CS101's program: Computer Science
├─ ✅ PASSED

VALIDATION CHECK #5: Year level OK?
├─ John's year_level: 1st Year (level = 1)
├─ CS101's year_level: 1st Year (level = 1)
├─ Is 1 <= 1? YES
├─ ✅ PASSED

VALIDATION CHECK #6: Prerequisites met?
├─ CS101 prerequisites: [] (NONE)
├─ ✅ PASSED (no prerequisites to check)

VALIDATION CHECK #7: Already enrolled?
├─ Query: SELECT * FROM enrollments
│         WHERE student_id = John AND subject_id = 1
│         AND academic_year_id = 1
├─ Result: NOT FOUND (John not already enrolled)
├─ ✅ PASSED

VALIDATION CHECK #8: Credit hours OK?
├─ John's current units: 0
├─ CS101 units: 3
├─ Total would be: 0 + 3 = 3 units
├─ Max: 18 units
├─ Is 3 <= 18? YES
├─ ✅ PASSED

✅ ALL CHECKS PASSED!
```

**Result:**

```sql
-- System creates enrollment record
INSERT INTO enrollments (
  student_id, subject_id, academic_year_id, status
)
VALUES (
  1, -- John's ID
  1, -- CS101's ID
  1, -- 2024-2025-1st Semester
  'Enrolled'
)
```

**John's Dashboard Now Shows:**

```
MY ENROLLED SUBJECTS (2024-2025-1st Semester)
┌─────────────────────────────────────────┐
│ CS101 - Introduction to Programming    │
│ Status: Enrolled                        │
│ Units: 3                                │
│ [Drop Course Button]                    │
└─────────────────────────────────────────┘

Total Units: 3 / 18
```

---

## STEP 10: STUDENT ENROLLS IN SECOND COURSE

**What John does:**
1. Click: **Available Courses**
2. Click: **[Enroll Button]** for CS102 (Data Structures)
3. System validates (same 8 checks)
4. ✅ All pass
5. Enrollment successful

**John's Dashboard Now Shows:**

```
MY ENROLLED SUBJECTS (2024-2025-1st Semester)
┌──────────────────────────────────────────┐
│ CS101 - Introduction to Programming     │
│ Status: Enrolled | Units: 3             │
│ [Drop Course]                            │
├──────────────────────────────────────────┤
│ CS102 - Data Structures                 │
│ Status: Enrolled | Units: 3             │
│ [Drop Course]                            │
└──────────────────────────────────────────┘

Total Units: 6 / 18
```

---

## STEP 11: BOB (2ND YEAR) TRIES TO ENROLL IN CS201

**What Bob does:**
1. Logs in
2. Views available courses
3. Sees CS201 - Advanced Programming
4. Clicks [Enroll]
5. System validates

```
VALIDATION CHECK #5: Year level OK?
├─ Bob's year_level: 2nd Year (level = 2)
├─ CS201's year_level: 2nd Year (level = 2)
├─ Is 2 <= 2? YES
├─ ✅ PASSED

VALIDATION CHECK #6: Prerequisites met?
├─ CS201 prerequisites: [1] (requires CS101)
├─ Query: SELECT * FROM enrollments
│         WHERE student_id = Bob AND subject_id = 1
│         AND status = 'Completed'
├─ Result: NOT FOUND (Bob has no completed CS101)
├─ ❌ FAILED

Error Message: "Cannot enroll: Must complete the following 
prerequisite course(s): CS101 - Introduction to Programming"
```

**Result:** ❌ Bob's enrollment is BLOCKED

**Why:** Bob never took CS101! He's 2nd year, but doesn't have it completed.

---

## STEP 12: BOB BACKTRACKS TO TAKE CS101

**What Bob does:**
1. Views available courses
2. Sees CS101 (1st Year course, but he can take it since year level = 1 <= his level = 2)
3. Clicks [Enroll] for CS101
4. System validates

```
VALIDATION CHECK #5: Year level OK?
├─ Bob's year_level: 2nd Year (level = 2)
├─ CS101's year_level: 1st Year (level = 1)
├─ Is 1 <= 2? YES (can take courses below or at his level)
├─ ✅ PASSED

VALIDATION CHECK #6: Prerequisites met?
├─ CS101 prerequisites: [] (NONE)
├─ ✅ PASSED

All other checks pass
```

**Result:** ✅ Bob enrolled in CS101

**Bob's Dashboard:**

```
MY ENROLLED SUBJECTS (2024-2025-1st Semester)
┌────────────────────────────────────────┐
│ CS101 - Introduction to Programming   │
│ Status: Enrolled | Units: 3           │
│ [Drop Course]                          │
└────────────────────────────────────────┘
```

---

## STEP 13: SEMESTER ENDS - GRADES SUBMITTED (FUTURE)

**Note: This happens when grading system is built**

```
Timeline:
├─ May 25: Classes end
├─ May 26 - Jun 15: Exam period
├─ Jun 20: Professor submits grades
│
└─ System update: (AUTOMATIC, when grading system is built)
   ├─ John took CS101 → Got grade A → Mark as 'Completed'
   ├─ John took CS102 → Got grade B → Mark as 'Completed'
   ├─ Bob took CS101 → Got grade C → Mark as 'Completed'
   └─ (All enrollments change from 'Enrolled' → 'Completed')
```

**Database Update:**

```sql
UPDATE enrollments 
SET status = 'Completed', grade = 4.0
WHERE student_id = John AND subject_id = 1;

UPDATE enrollments 
SET status = 'Completed', grade = 3.0
WHERE student_id = John AND subject_id = 2;

UPDATE enrollments 
SET status = 'Completed', grade = 2.0
WHERE student_id = Bob AND subject_id = 1;
```

---

## STEP 14: NEXT SEMESTER STARTS - NEW REGISTRATION

**Academic calendar for 2024-2025-2nd Semester set by admin:**

```
├─ Year Code: 2024-2025
├─ Semester: 2nd Semester
├─ Registration Start: 2025-05-01
├─ Registration End: 2025-05-31
```

**John (now upgraded to 2nd Year) tries to enroll:**

**System logic:**
- John's status changed: 1st Year → 2nd Year (happens at semester boundary, admin does this)
- John has completed CS101 ✓
- John has completed CS102 ✓

**John tries to enroll in CS201:**

```
VALIDATION CHECK #5: Year level OK?
├─ John's year_level: 2nd Year (level = 2)
├─ CS201's year_level: 2nd Year (level = 2)
├─ Is 2 <= 2? YES
├─ ✅ PASSED

VALIDATION CHECK #6: Prerequisites met?
├─ CS201 prerequisites: [1] (requires CS101)
├─ Query: SELECT * FROM enrollments
│         WHERE student_id = John AND subject_id = 1
│         AND status = 'Completed'
├─ Result: FOUND ✅ (John has completed CS101)
├─ ✅ PASSED

All other checks pass
```

**Result:** ✅ John successfully enrolls in CS201!

---

## PART 5: THE COMPLETE SYSTEM FLOW DIAGRAM

```
START OF SEMESTER
      ↓
┌─────────────────────────────────────────────┐
│ 1. ADMIN SETUP (one-time)                  │
│    ├─ Create academic calendar              │
│    ├─ Create programs                       │
│    ├─ Create courses                        │
│    └─ Create students                       │
└─────────────────────────────────────────────┘
      ↓
┌─────────────────────────────────────────────┐
│ 2. REGISTRATION PERIOD OPENS                │
│    (If registration_start_date <= today <=  │
│     registration_end_date)                   │
└─────────────────────────────────────────────┘
      ↓
┌─────────────────────────────────────────────┐
│ 3. STUDENTS LOG IN & BROWSE COURSES         │
│    ├─ System shows only courses for their   │
│    │  year level                             │
│    └─ Students see available courses        │
└─────────────────────────────────────────────┘
      ↓
┌─────────────────────────────────────────────┐
│ 4. STUDENT CLICKS ENROLL                    │
│                                             │
│ 8-POINT VALIDATION (all must pass):         │
│ 1. ✓ Registration period open?              │
│ 2. ✓ Student status = Active?               │
│ 3. ✓ Course is active?                      │
│ 4. ✓ Same program?                          │
│ 5. ✓ Year level OK?                         │
│ 6. ✓ Prerequisites completed?               │
│ 7. ✓ Not already enrolled?                  │
│ 8. ✓ Credit hours under 18?                 │
└─────────────────────────────────────────────┘
      ↓
    ✅ IF ALL PASS              ❌ IF ANY FAIL
      ↓                           ↓
┌──────────────────────┐   ┌──────────────────────┐
│ CREATE ENROLLMENT    │   │ SHOW ERROR MESSAGE   │
│ INSERT INTO          │   │ Enrollment blocked   │
│ enrollments VALUES   │   │ Student tries again  │
│ (student, course,    │   └──────────────────────┘
│  year, 'Enrolled')   │
└──────────────────────┘
      ↓
┌─────────────────────────────────────────────┐
│ 5. STUDENT SEES COURSE IN DASHBOARD         │
│    ├─ CS101 - Intro Programming             │
│    ├─ Status: Enrolled                      │
│    ├─ Units: 3                              │
│    └─ Total Units So Far: 3/18              │
└─────────────────────────────────────────────┘
      ↓
┌─────────────────────────────────────────────┐
│ 6. ADD/DROP DEADLINE PASSES                 │
│    (if add_drop_deadline < today)            │
│    Students CAN NO LONGER DROP COURSES      │
└─────────────────────────────────────────────┘
      ↓
┌─────────────────────────────────────────────┐
│ 7. SEMESTER CONTINUES                       │
│    ├─ Classes: Jan 20 - May 25              │
│    ├─ Midterms: Mar 1 - Mar 14              │
│    └─ Finals: May 26 - Jun 15               │
└─────────────────────────────────────────────┘
      ↓
┌─────────────────────────────────────────────┐
│ 8. GRADES SUBMITTED (FUTURE)                │
│    ├─ Professor enters grades               │
│    ├─ Enrollment status changes:            │
│    │  'Enrolled' → 'Completed'              │
│    └─ Grade stored in database              │
└─────────────────────────────────────────────┘
      ↓
┌─────────────────────────────────────────────┐
│ 9. NEXT SEMESTER STARTS                     │
│    ├─ Year level advanced (1st → 2nd)       │
│    ├─ Prerequisites now available for use   │
│    └─ New registration period opens         │
└─────────────────────────────────────────────┘
      ↓
REPEAT FROM STEP 2
```

---

## PART 6: KEY CONCEPTS EXPLAINED

### Concept 1: Enrollment Status

```
Current System:
├─ 'Enrolled' = Student is currently taking the course

Future (when grading system built):
├─ 'Enrolled' = Taking now
├─ 'Completed' = Finished with passing grade
├─ 'Failed' = Did not pass
└─ 'Dropped' = Withdrew
```

### Concept 2: Prerequisites

```
Example: CS201 requires CS101

Logic:
├─ Admin checks: "This course requires CS101"
├─ Student tries to enroll in CS201
├─ System checks: "Does student have CS101 with status='Completed'?"
│
├─ If YES → Allow enrollment ✅
└─ If NO  → Block enrollment ❌
         (Can't take CS201 until CS101 is Completed)
```

### Concept 3: Year Level Progression

```
Timeline:
├─ Start of Year 1: year_level = '1st Year'
│  Can take: 1st Year courses
│  Cannot: 2nd+ Year courses
│
├─ After Year 1 completes: year_level = '2nd Year'
│  Can take: 1st + 2nd Year courses
│  Cannot: 3rd+ Year courses
│
├─ After Year 2 completes: year_level = '3rd Year'
│  Can take: 1st, 2nd + 3rd Year courses
│
└─ After Year 3 completes: year_level = '4th Year'
   Can take: All courses (1st-4th Year)
```

### Concept 4: Credit Hours

```
Limit: 18 units per semester

If student wants to take:
├─ CS101: 3 units
├─ CS102: 3 units
├─ CS103: 4 units
├─ MATH101: 4 units
└─ Total: 3+3+4+4 = 14 units ✅ (under 18)

If tries to add CS104: 5 units:
├─ Would be: 14 + 5 = 19 units
├─ 19 > 18? YES
├─ ❌ BLOCKED: "Would exceed 18 units"
```

---

## PART 7: COMMON QUESTIONS

### Q: Can a 1st year student take a 2nd year course?
**A:** No. Year level validates: "Cannot take courses ABOVE your level"

### Q: Can a 2nd year student take a 1st year course?
**A:** Yes. 1st Year level (1) <= 2nd Year level (2) ✅

### Q: What if prerequisites are incomplete?
**A:** Enrollment blocked. Student must take prerequisite first.

### Q: Can a student change programs mid-semester?
**A:** Only through admin change. Enrollments not automatically updated.

### Q: What happens after a student graduates?
**A:** Admin changes status to "Graduated". They can't enroll in new courses.

---

## PART 8: THE DATABASE (What Actually Gets Stored)

### Table 1: academic_years
```sql
id | year_code  | semester     | registration_start | is_current
1  | 2024-2025  | 1st Semester | 2024-12-01        | true
```

### Table 2: programs
```sql
id | code | name                                    | is_active
1  | CS   | Bachelor of Science in Computer Science | true
```

### Table 3: subjects
```sql
id | code  | name                  | program_id | year_level | prerequisites
1  | CS101 | Intro Programming     | 1          | 1st Year   | []
2  | CS102 | Data Structures       | 1          | 1st Year   | []
3  | CS201 | Advanced Programming  | 1          | 2nd Year   | [1]
```

### Table 4: students
```sql
id | student_id | first_name | last_name | program_id | year_level | status
1  | 2024-001   | John       | Doe       | 1          | 1st Year   | Active
2  | 2024-002   | Jane       | Smith     | 1          | 1st Year   | Active
3  | 2024-003   | Bob        | Johnson   | 1          | 2nd Year   | Active
```

### Table 5: enrollments
```sql
id | student_id | subject_id | academic_year_id | status    | grade
1  | 1          | 1          | 1                | Enrolled  | NULL
2  | 1          | 2          | 1                | Enrolled  | NULL
3  | 2          | 1          | 1                | Enrolled  | NULL
```

---

## SUMMARY

**SIMS is a 5-step system:**

1. **Admin sets up** programs, courses, calendar, and students
2. **Registration opens** (dates defined in academic calendar)
3. **Students log in** and see available courses (filtered by year level)
4. **Students click enroll** → System validates 8 checks → Creates enrollment record
5. **Semester progresses** → Grades entered → Status changes to Completed → Next semester prerequisites work

**The prerequisite system specifically works like this:**
- Admin checks "This course requires XYZ"
- Student tries to enroll
- System checks: "Does student have XYZ completed?"
- If YES → Enrollment allowed
- If NO → Enrollment blocked with error message

**That's the entire system from start to finish!**
