# Where Can Users Set the Academic Year?

## Short Answer: **NOWHERE - There's No UI For It**

---

## Current State

### Navigation Menu (NO LINK)
```
Dashboard
├─ Students
├─ Programs
├─ Subjects
├─ Reports
├─ Archives
├─ Activity Log
└─ ❌ MISSING: Academic Years
```

### Dashboard (NO LINK)
Quick Actions show:
- Add New Student
- View All Students
- Generate Reports

**❌ Missing:** Academic Years management

### Reason
- **Route exists**: `Route::resource('academic-years', AcademicYearController::class)`
- **Controller is empty**: No methods implemented
- **No views exist**: No UI pages built
- **No navigation link**: Can't find it from UI

---

## How Academic Year Currently Works

### What's in the Database

Currently, **2 academic years** are stored (seeded):

```
Academic Year 1:
├─ ID: 1
├─ Year Code: 2024-2025-1
├─ Semester: 1st Semester
├─ Start: 2024-08-01
├─ End: 2024-12-31
├─ Registration: 2024-08-01 to 2024-08-15 ⚠️ EXPIRED
├─ Is Current: YES ✅
└─ Other dates: Midterm, exam periods, etc.

Academic Year 2:
├─ ID: 2
├─ Year Code: 2024-2025-2
├─ Semester: 2nd Semester
├─ Start: 2025-01-01
├─ End: 2025-05-31
├─ Registration: 2024-12-15 to 2025-01-05 ⚠️ EXPIRED
├─ Is Current: NO
└─ Other dates: Set but outdated
```

### The Problem

**Registration period expired on Aug 15, 2024**
- Today: November 26, 2025
- Result: **Students CAN'T ENROLL** (registration period has closed)

---

## How to Access Academic Year Currently

### Method 1: Direct URL (Broken)
```
http://localhost:8000/academic-years
→ Error 500 or blank page (controller empty)

http://localhost:8000/academic-years/create
→ Error 500 (no create view)

http://localhost:8000/academic-years/1/edit
→ Error 500 (no edit view)
```

### Method 2: Database Query (Advanced)
```php
// In artisan tinker:
$year = App\Models\AcademicYear::where('is_current', true)->first();
echo $year->year_code; // 2024-2025-1
echo $year->registration_start_date; // 2024-08-01
```

### Method 3: Direct Database Edit (Not Recommended)
```sql
-- Update current year
UPDATE academic_years SET is_current = false WHERE id = 1;
UPDATE academic_years SET is_current = true WHERE id = 2;

-- Update registration dates
UPDATE academic_years 
SET registration_start_date = '2025-11-01',
    registration_end_date = '2025-12-31'
WHERE id = 1;
```

### Method 4: Laravel Seeder Edit (For Developers)
Edit `database/seeders/AcademicYearSeeder.php` and re-run:
```bash
php artisan db:seed --class=AcademicYearSeeder
```

---

## The Real Problem

### Why Students Can't Enroll

```
Student tries to enroll
    ↓
System checks: Is today within registration period?
    ↓
Registration period: Aug 1-15, 2024
Today: Nov 26, 2025
    ↓
Aug 15 < Nov 26?  YES
    ↓
❌ ENROLLMENT BLOCKED
"Registration period has closed. Contact the Registrar for late registration."
```

### Code Location
`app/Http/Controllers/StudentSubjectController.php` (line 109-117):
```php
if ($currentAcademicYear->registration_start_date && 
    $currentAcademicYear->registration_end_date) {
    $today = now()->toDateString();
    if ($today > $currentAcademicYear->registration_end_date->toDateString()) {
        return back()->with('error', 'Registration period has closed.');
    }
}
```

---

## What Needs to Be Done

### Option 1: Quick Fix (Immediate)
Update the database dates to valid dates:

```sql
UPDATE academic_years 
SET registration_start_date = '2025-11-01',
    registration_end_date = '2025-12-31',
    classes_start_date = '2025-11-15',
    classes_end_date = '2025-12-15'
WHERE id = 1;
```

**Result:** Students can enroll immediately

### Option 2: Implement Academic Year Management UI (Proper)

Build the full AcademicYearController:

**Files to create/update:**

1. **Controller**: `app/Http/Controllers/AcademicYearController.php`
   - `index()` - List all academic years
   - `create()` - Show create form
   - `store()` - Save new year
   - `edit()` - Show edit form
   - `update()` - Save changes
   - `destroy()` - Delete year
   - `setCurrent()` - Mark as current

2. **Views** (new files):
   - `resources/views/academic-years/index.blade.php`
   - `resources/views/academic-years/create.blade.php`
   - `resources/views/academic-years/edit.blade.php`

3. **Navigation**: Update `resources/views/layouts/navigation.blade.php`
   - Add link to Academic Years management

4. **Dashboard**: Update `resources/views/dashboard.blade.php`
   - Add Academic Years info/status

**After implementation, admin would:**
1. Click **Academic Years** in navigation
2. See list of all years
3. Click **Edit** to change dates
4. Click button to set as current

---

## Routes That Already Exist

These routes are defined in `routes/web.php` (lines 46-48):

```php
Route::resource('academic-years', AcademicYearController::class);
Route::post('/academic-years/{academicYear}/set-current', 
    [AcademicYearController::class, 'setCurrent'])->name('academic-years.set-current');
```

**BUT the controller methods do nothing.**

---

## Summary Table

| What | Status | Details |
|------|--------|---------|
| Database | ✅ Exists | 2 years seeded |
| Model | ✅ Exists | AcademicYear.php |
| Routes | ✅ Defined | In web.php |
| Controller | ❌ Empty | Just skeleton |
| Views | ❌ Missing | Not created |
| Navigation | ❌ Missing | No link |
| UI Access | ❌ Broken | Can't access |

---

## Temporary Workaround

If you need students to enroll RIGHT NOW, use this SQL:

```sql
UPDATE academic_years 
SET 
  registration_start_date = '2025-11-20',
  registration_end_date = '2025-12-31',
  add_drop_deadline = '2025-12-15',
  classes_start_date = '2025-11-25',
  classes_end_date = '2025-12-20',
  midterm_start_date = '2025-12-01',
  midterm_end_date = '2025-12-05',
  exam_start_date = '2025-12-21',
  exam_end_date = '2025-12-31'
WHERE id = 1;
```

Then students can enroll between Nov 20 - Dec 31, 2025.

---

## What We Need to Build

To properly implement Academic Years management:

```
AcademicYearController.php (200+ lines)
├─ index() - Query all, pass to view
├─ create() - Pass empty form to view
├─ store() - Validate, save, redirect
├─ edit() - Load year, pass to view
├─ update() - Validate, update, redirect
├─ destroy() - Delete, redirect
└─ setCurrent() - Update is_current flag

Views (3 files)
├─ academic-years/index.blade.php
│  └─ Table of years, Edit/Delete buttons
├─ academic-years/create.blade.php
│  └─ Form to create new year
└─ academic-years/edit.blade.php
   └─ Form to edit existing year

Navigation
└─ Add link: "Academic Years"
```

**Estimated effort:** 1-2 hours to implement fully

---

## Answer to Your Question

**"Where can they set the academic year?"**

**Currently:** 
- **Not available** in the UI at all
- Must use database directly or artisan tinker
- This is a **gap in the system**

**After implementation:**
- Click **Academic Years** in navigation
- See all years, edit dates, set as current
- Changes take effect immediately

**For now (workaround):**
- Use SQL to update dates in database
- Or contact support/developer to update it
