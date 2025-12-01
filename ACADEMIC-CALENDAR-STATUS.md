# Academic Calendar/Years Status

## Summary: PARTIALLY IMPLEMENTED

The academic calendar system is **partially functional**:
- ✅ **Database**: Academic years exist and are stored
- ✅ **Model**: AcademicYear model is defined
- ✅ **Routes**: Routes are defined for CRUD operations
- ❌ **Controller**: Controller is **EMPTY** (just skeleton methods)
- ❌ **Views**: No views implemented
- ⚠️ **Usage**: Data is used by enrollment validation but can't be managed through UI

---

## What Exists in Database

```
Academic Year 1:
├─ ID: 1
├─ Year Code: 2024-2025-1
├─ Semester: 1st Semester
├─ Start Date: 2024-08-01
├─ End Date: 2024-12-31
├─ Registration Period: 2024-08-01 to 2024-08-15
└─ Is Current: YES ✅

Academic Year 2:
├─ ID: 2
├─ Year Code: 2024-2025-2
├─ Semester: 2nd Semester
├─ Start Date: 2025-01-01
├─ End Date: 2025-05-31
├─ Registration Period: 2024-12-15 to 2025-01-05
└─ Is Current: NO
```

---

## Where Academic Calendar is Used

### 1. **Enrollment Validation** (StudentSubjectController.php)
```php
// Line 47-50: Gets current academic year
$currentAcademicYear = AcademicYear::where('is_current', true)->first();
if (!$currentAcademicYear) {
    return back()->with('error', 'No active academic year set');
}

// Line 109-117: Validates registration period
if ($currentAcademicYear->registration_start_date && 
    $currentAcademicYear->registration_end_date) {
    $today = now()->toDateString();
    if ($today < $currentAcademicYear->registration_start_date->toDateString()) {
        return back()->with('error', 'Registration period has not yet opened.');
    }
    if ($today > $currentAcademicYear->registration_end_date->toDateString()) {
        return back()->with('error', 'Registration period has closed.');
    }
}
```

**Impact:** If registration dates are wrong, students can't enroll even if they should be able to.

### 2. **Credit Hour Limits** (Line 90-106)
```php
$currentEnrollments = $student->enrollments()
    ->where('academic_year_id', $currentAcademicYear->id)
    ->where('status', 'Enrolled')
    ->with('subject')
    ->get();
```

**Impact:** Students can only enroll up to 18 units in the current academic year.

---

## What's Missing (Controller)

### The AcademicYearController is completely empty:

```php
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()         // ❌ Empty
    public function create()        // ❌ Empty
    public function store()         // ❌ Empty
    public function show()          // ❌ Empty
    public function edit()          // ❌ Empty
    public function update()        // ❌ Empty
    public function destroy()       // ❌ Empty
    public function setCurrent()    // ❌ Empty (custom route)
}
```

**Result:** You CAN'T manage academic years through the admin UI.

---

## How Data Currently Exists

The academic years were **created by a seeder** (database seed file), not through the UI:

```
DatabaseSeeder
  └─ AcademicYearSeeder (probably)
      └─ Creates: 2024-2025-1 and 2024-2025-2
```

---

## What Happens If You Try to Access Admin UI

### If you go to: `http://localhost:8000/academic-years`
- ❌ **Error 500** or blank page
- Reason: `index()` method does nothing

### If you try to create: `http://localhost:8000/academic-years/create`
- ❌ **Error 500** or blank page
- Reason: `create()` method does nothing + no view exists

### Result: **No UI for managing academic calendar**

---

## How to Check Current Academic Year

**Currently**, the only way to see academic years is:
1. Check database directly
2. Check `StudentSubjectController.php` line 47 to see how it's queried
3. Run a custom tinker command

```php
// In artisan tinker:
$year = App\Models\AcademicYear::where('is_current', true)->first();
echo $year->year_code; // Output: "2024-2025-1"
```

---

## How to Change Current Academic Year

**Currently**, the only way to change which year is current:
1. Direct database update:
```sql
UPDATE academic_years SET is_current = false;
UPDATE academic_years SET is_current = true WHERE id = 2;
```

2. Or manually via seed file modification

**There's NO admin UI button to do this.**

---

## Issues with Current Setup

### Issue 1: Registration Period May Be Wrong
```
Current: 2024-08-01 to 2024-08-15
Today: 2025-11-26 (November 26, 2025)

Result: ❌ BLOCKED - Registration has closed!
Students can't enroll even in current semester
```

**Why?** The seeded academic year has Aug-Aug registration dates, which are in the past.

### Issue 2: No Way to Update Dates
- Admin can't change registration dates through UI
- Must edit database directly or create new seeder
- This is a **gap** you identified earlier

### Issue 3: Second Semester is Marked "Not Current"
```
2024-2025-2 (2nd Semester):
├─ Start: 2025-01-01
├─ End: 2025-05-31
├─ Is Current: NO ❌
```

**Result:** If trying to use 2nd semester, need to manually set `is_current = true` in database.

---

## What You Should Do

### Option A: Implement the Controller (RECOMMENDED)
Build the full AcademicYearController with:
- ✅ index() - List all academic years
- ✅ create() - Show form to create new year
- ✅ store() - Save new year to database
- ✅ edit() - Show form to edit existing year
- ✅ update() - Save changes
- ✅ destroy() - Delete year
- ✅ setCurrent() - Mark year as current (custom route)

Plus create the views:
- `academic-years/index.blade.php`
- `academic-years/create.blade.php`
- `academic-years/edit.blade.php`

### Option B: Quick Fix
Update the seeded academic years to have correct dates:
1. Edit `database/seeders/AcademicYearSeeder.php`
2. Change registration dates to current/future dates
3. Re-run seeder: `php artisan db:seed --class=AcademicYearSeeder`

### Option C: Manual Fix (For Now)
Update database directly:
```sql
UPDATE academic_years 
SET registration_start_date = '2025-11-01',
    registration_end_date = '2025-12-31'
WHERE id = 1;
```

---

## Database Schema

```sql
CREATE TABLE academic_years (
    id INTEGER PRIMARY KEY,
    year_code VARCHAR(20) UNIQUE,
    semester ENUM('1st Semester', '2nd Semester', 'Summer'),
    start_date DATE,
    end_date DATE,
    is_current BOOLEAN DEFAULT false,
    
    -- Calendar dates (added later)
    registration_start_date DATE NULLABLE,
    registration_end_date DATE NULLABLE,
    add_drop_deadline DATE NULLABLE,
    classes_start_date DATE NULLABLE,
    classes_end_date DATE NULLABLE,
    midterm_start_date DATE NULLABLE,
    midterm_end_date DATE NULLABLE,
    exam_start_date DATE NULLABLE,
    exam_end_date DATE NULLABLE,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

---

## Real Answer to Your Question

**"Is there really an academic calendar?"**

**Answer:** 
- **In Database?** YES ✅ (2 years exist)
- **In Code?** PARTIALLY ⚠️ (Model exists, controller empty)
- **In Admin UI?** NO ❌ (No controller/views)
- **Actually Used?** YES ✅ (For enrollment validation)

**The system depends on academic calendar data to work, but you can't manage it through the UI yet.**

**Current Status:**
- Academic years were seeded/created once
- Enrollment validation uses them
- But data is now outdated (registration ended 2024-08-15, today is 2025-11-26)
- So students probably CAN'T ENROLL because registration dates have passed

**That's why the system might feel broken - the academic calendar data is stale!**
