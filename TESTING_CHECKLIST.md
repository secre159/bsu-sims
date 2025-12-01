# Testing Checklist - BSU SIMS

## Quick Start
1. Start the application: `php artisan serve`
2. Login with: `admin@bsu-bokod.edu.ph`
3. Navigate to various pages and check each item

---

## 1. Students Page (`/students`)

### View All Students
- [ ] List shows 50+ test students
- [ ] Filtering by Status works (Active, On Leave, Graduated, Dropped)
- [ ] Filtering by Program works
- [ ] Filtering by Year Level works
- [ ] Search by Student ID works
- [ ] Pagination works (50 per page)
- [ ] Sort by name, status, year level works

### Individual Student: 2024-ACTIVE-001
- [ ] Name: Scholar Active Test
- [ ] Status shows: Active
- [ ] Year Level: 1st Year
- [ ] Can see 4 current enrollments
- [ ] Edit button available
- [ ] Delete button available (if no enrollments)

### Individual Student: 2024-DROPOUT-001
- [ ] Name: CourseD Quitter Test
- [ ] Status shows: Active
- [ ] Has 1 "Dropped" course and 4 "Enrolled" courses
- [ ] Can view enrollment history
- [ ] Drop date/time shown in remarks
- [ ] Audit trail shows drop action

### Individual Student: 2024-FAILED-001
- [ ] Name: UnluckyD Flunked Test
- [ ] Has 1 course with status "Failed" and grade "4.0"
- [ ] Remarks show "Failed - needs retake"
- [ ] Other 3 courses show as "Enrolled"
- [ ] Audit trail shows failed enrollment

### Individual Student: 2024-COMPLETED-001
- [ ] Name: Course Finished Test
- [ ] Status: Active, Year Level: 2nd Year
- [ ] Has 4 "Completed" courses with grades (1.0, 1.25, 1.5, 2.0)
- [ ] Has 1 "Enrolled" course (CS102)
- [ ] **Can enroll in CS102** (has CS101 completed - prerequisite satisfied)
- [ ] Audit trail shows enrollment actions

### Individual Student: 2024-LEAVE-001
- [ ] Name: OnLeave Absent Test
- [ ] Status shows: "On Leave"
- [ ] **Has NO current enrollments** (empty list)
- [ ] Can view full profile
- [ ] Can change status back to Active

### Individual Student: 2024-GRAD-001
- [ ] Name: Graduate Valedictorian Test
- [ ] Status shows: "Graduated"
- [ ] Year Level: 4th Year
- [ ] Shows 10 completed courses with various grades
- [ ] Cannot enroll in new courses (UI should prevent or show message)
- [ ] Transcript shows all courses with grades

### Individual Student: 2024-DROPPED-001
- [ ] Name: Program Discontinued Test
- [ ] Status shows: "Dropped"
- [ ] Has 2 enrollments - both "Dropped"
- [ ] Cannot enroll in new courses (dropped status blocks)
- [ ] Audit trail shows when dropped

### Year Level Students
- [ ] `2024-2NDYEAR-001` - 2nd Year, 4 subjects enrolled
- [ ] `2024-3RDYEAR-001` - 3rd Year, 4 subjects enrolled
- [ ] `2024-4THYEAR-001` - 4th Year, 4 subjects enrolled
- [ ] `2024-5THYEAR-001` - 5th Year, 4 subjects enrolled
- [ ] Each shows subjects only for their year level

---

## 2. Enrollment Management

### Student: 2024-ACTIVE-001
- [ ] View Enrollments button/link works
- [ ] Shows 4 enrolled courses
- [ ] Each course shows: code, name, units, status
- [ ] Can see course descriptions
- [ ] Drop button appears for enrolled courses
- [ ] **Try to drop a course:**
  - [ ] Course status changes to "Dropped"
  - [ ] Remarks field shows drop date/time
  - [ ] Audit trail logs the drop action
  - [ ] History preserved (can see it was dropped)

### Prerequisite Testing
- [ ] Student 2024-COMPLETED-001 can enroll in CS102 (has CS101 completed)
- [ ] If trying to enroll in CS201 without CS102:
  - [ ] System blocks with error message
  - [ ] Shows list of missing prerequisites
  - [ ] Cannot proceed with enrollment

### Inactive Subject
- [ ] BIT101 is marked inactive (is_active = false)
- [ ] Inactive subjects don't appear in enrollment dropdown
- [ ] Existing enrollments in BIT101 are preserved
- [ ] Audit trail shows when BIT101 was deactivated

---

## 3. Audit Trail (`/activities`)

### Activity Page Load
- [ ] Page loads without errors
- [ ] Shows list of recent activities
- [ ] **Should show 32+ activities** (from seeding)
- [ ] Activities show: Description, Action, Timestamp, User

### Activity Types
- [ ] **Created** activities (6 students, 5 subjects, 3 programs)
  - [ ] Shows student_id, program, year level
  - [ ] Shows code and name for subjects/programs
  
- [ ] **Enrolled** activities (15+ entries)
  - [ ] Shows student ID and subject code
  - [ ] Shows academic year
  - [ ] Timestamp recorded
  
- [ ] **Dropped** activities
  - [ ] Shows which student dropped which course
  - [ ] Shows timestamp of drop
  - [ ] Remarks captured

- [ ] **Completed** activities
  - [ ] Shows student, subject, and grade
  - [ ] Timestamp recorded

- [ ] **Failed** activities
  - [ ] Shows student, subject, grade (4.0)
  - [ ] Indicates needs retake

- [ ] **Updated** activities
  - [ ] Shows changes with old → new values
  - [ ] Examples: status changes (Active → Graduated → Active)
  - [ ] Subject deactivation (is_active: true → false)

### Sorting & Filtering
- [ ] Activities sort by newest first (default)
- [ ] Can sort by oldest first
- [ ] Can filter by action type (if implemented)
- [ ] Can search by student ID (if implemented)
- [ ] Can filter by date range (if implemented)

---

## 4. Student Profile Edit

### Edit Student: 2024-ACTIVE-001
- [ ] All fields display current values
- [ ] Can change status
- [ ] Can change year level
- [ ] **Status validation:**
  - [ ] 1st Year student - can't mark as Graduated
  - [ ] 2nd Year student - can't mark as Graduated
  - [ ] 3rd Year student - can't mark as Graduated
  - [ ] 4th Year student - CAN mark as Graduated
- [ ] Can save changes
- [ ] Audit trail logs the update with old/new values

### Test Status Transitions
- [ ] Change student status: Active → On Leave
  - [ ] Audit trail shows transition
  - [ ] Student list reflects new status
  
- [ ] Change status: On Leave → Active
  - [ ] Audit trail shows transition
  - [ ] Can now enroll in courses

- [ ] Try to graduate 1st Year student
  - [ ] Should get validation error
  - [ ] Status not changed
  - [ ] Error message explains year level requirement

---

## 5. Subjects & Programs

### Subjects Page (`/subjects`)
- [ ] List shows all subjects
- [ ] Can filter by program
- [ ] Can filter by year level
- [ ] BIT101 shows as inactive (is_active = false)
- [ ] Click on inactive subject to view
- [ ] Audit trail shows when deactivated

### Programs Page (`/programs`)
- [ ] List shows all programs
- [ ] Each program shows student count
- [ ] Click on program to see enrolled students
- [ ] Program audit trail shows creation events

---

## 6. Academic Years

### Academic Years Page (`/academic-years`)
- [ ] List shows all academic years
- [ ] Shows current year (marked as current)
- [ ] Can view 2024-2025-1st Semester
- [ ] Calendar dates display correctly
- [ ] Can edit dates
- [ ] Can set new year as current
- [ ] Audit trail logs:
  - [ ] Year creation
  - [ ] Year updates
  - [ ] When year set as current

---

## 7. Data Integrity Checks

### Cannot Perform Invalid Actions
- [ ] Cannot enroll dropped student in courses
- [ ] Cannot enroll on-leave student in courses
- [ ] Cannot enroll without meeting prerequisites
- [ ] Cannot enroll in inactive subjects
- [ ] Cannot graduate 1st-3rd year students
- [ ] Cannot create duplicate student ID

### History Preservation
- [ ] Dropped enrollments show drop timestamp
- [ ] Failed enrollments show grade and "needs retake"
- [ ] Completed enrollments show grade
- [ ] Can view full history of student actions
- [ ] Audit trail never deletes (only adds)

### Date Handling
- [ ] All dates display in correct format (Y-m-d)
- [ ] Academic year dates show correctly
- [ ] Enrollment dates preserved
- [ ] Drop dates/times recorded
- [ ] Audit trail timestamps accurate

---

## 8. Common Bugs to Find

### If You Find These, Log Them:

- [ ] Audit trail shows no entries or errors loading
- [ ] Dropped courses still allow new enrollments
- [ ] On-leave students can enroll when they shouldn't
- [ ] Prerequisites not enforced
- [ ] Status validation not working (can graduate 1st year)
- [ ] Inactive subjects still appear in dropdowns
- [ ] Dates not formatting correctly
- [ ] Pagination doesn't work
- [ ] Filters not filtering
- [ ] Search not working
- [ ] Deleted students still appear in list
- [ ] Grades not saving for completed courses
- [ ] Audit trail has wrong timestamps
- [ ] User ID not recorded in activities

---

## 9. Performance Checks

- [ ] Students list loads in <1 second (50 records)
- [ ] Enrollments page loads quickly
- [ ] Activity log doesn't lag with 30+ entries
- [ ] Search/filter responsive
- [ ] No database errors in logs
- [ ] No N+1 query problems

---

## 10. UI/UX Checks

### Messaging
- [ ] Error messages are clear and helpful
- [ ] Success messages show on update
- [ ] Validation errors point to specific field
- [ ] Toast/alert notifications work

### Navigation
- [ ] Sidebar links work correctly
- [ ] Breadcrumbs accurate
- [ ] Back buttons work
- [ ] Edit/Delete/View buttons clearly labeled

### Forms
- [ ] Form fields are properly labeled
- [ ] Required fields marked with *
- [ ] Date pickers work correctly
- [ ] Dropdowns show correct options
- [ ] Checkboxes/radio buttons functional

---

## Testing Order (Recommended)

1. **Start with Students** → Verify all scenarios exist
2. **Check Enrollments** → Verify statuses and history
3. **Test Audit Trail** → Verify logging working
4. **Test Constraints** → Try invalid operations
5. **Verify Data** → Check integrity
6. **Performance** → Check load times
7. **UI/UX** → Check messages and navigation

---

## Report Issues

When you find a problem, note:
1. **What you were doing** (step by step)
2. **What you expected** to happen
3. **What actually happened**
4. **Error message** (if any)
5. **Student ID** or data you were testing with
6. **Screenshot** (if possible)

---

**Happy Testing! 🧪**
