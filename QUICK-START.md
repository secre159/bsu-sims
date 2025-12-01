# Quick Start Guide - BSU-Bokod SIMS

## 🚀 Get Started in 5 Minutes

### Step 1: Run Migrations
```powershell
cd "C:\Users\Axl Chan\Desktop\bsu-sims"
php artisan migrate:fresh --seed
```

This creates:
- All database tables
- Admin user: `admin@bsu-bokod.edu.ph` / `password`
- 7 sample programs (BSIT, BSEd, BSAgri, etc.)
- Academic year 2024-2025

### Step 2: Start the Server
```powershell
php artisan serve
```

Visit: **http://127.0.0.1:8000**

### Step 3: Login
- Email: `admin@bsu-bokod.edu.ph`
- Password: `password`

---

## 📋 What's Already Done (Skeleton)

✅ Database structure (migrations)  
✅ Models with relationships  
✅ Authentication (login/logout)  
✅ Routes defined  
✅ Controllers created (empty)  
✅ Views created (skeleton)  
✅ Navigation menu  
✅ Dashboard layout  

## 🔨 What You Need to Implement

### Priority 1: Basic Functionality
1. **DashboardController** - Show statistics
2. **StudentController** - CRUD operations
3. **Form Validation** - Add validation rules
4. **Search/Filter** - Search students by name/ID

### Priority 2: Complete Features
5. **ProgramController** - Manage programs
6. **ReportController** - Generate reports
7. **Export** - PDF/Excel exports

### Priority 3: Polish
8. **Photo Upload** - Student photos
9. **Pagination** - For student lists
10. **Audit Trail** - Track changes

---

## 📁 File Structure Quick Reference

### Controllers (app/Http/Controllers/)
- `DashboardController.php` - ⚠️ Empty, needs implementation
- `StudentController.php` - ⚠️ Empty, needs implementation
- `ProgramController.php` - ⚠️ Empty, needs implementation
- `ReportController.php` - ⚠️ Empty, needs implementation

### Models (app/Models/)
- `Student.php` - ✅ Complete with relationships
- `Program.php` - ✅ Complete with relationships
- `AcademicYear.php` - ✅ Complete
- `StudentHistory.php` - ✅ Complete

### Views (resources/views/)
- `dashboard.blade.php` - ✅ Skeleton ready
- `students/index.blade.php` - ✅ Skeleton ready
- `students/create.blade.php` - ✅ Form ready
- `students/edit.blade.php` - ⚠️ Needs completion
- `students/show.blade.php` - ✅ Skeleton ready
- `programs/index.blade.php` - ✅ Skeleton ready
- `reports/index.blade.php` - ✅ Skeleton ready

---

## 💡 Implementation Tips

### 1. Start with DashboardController
```php
// app/Http/Controllers/DashboardController.php
public function index()
{
    $totalStudents = Student::count();
    $activeStudents = Student::where('status', 'Active')->count();
    $totalPrograms = Program::count();
    $graduatedStudents = Student::where('status', 'Graduated')->count();

    return view('dashboard', compact(
        'totalStudents', 'activeStudents', 'totalPrograms', 'graduatedStudents'
    ));
}
```

### 2. Then StudentController->index()
```php
public function index(Request $request)
{
    $students = Student::with('program')
        ->when($request->search, function($query, $search) {
            $query->where('student_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
        })
        ->when($request->program, function($query, $program) {
            $query->where('program_id', $program);
        })
        ->paginate(15);

    $programs = Program::all();

    return view('students.index', compact('students', 'programs'));
}
```

### 3. Create Form Request for Validation
```powershell
php artisan make:request StoreStudentRequest
```

---

## 🎯 Testing Checklist

After implementing each feature, test:
- [ ] Can view dashboard with statistics
- [ ] Can add new student
- [ ] Can edit student
- [ ] Can view student details
- [ ] Can delete student
- [ ] Can search students
- [ ] Can filter by program
- [ ] Can manage programs
- [ ] Can generate reports

---

## ⚡ Common Commands

```powershell
# Clear all caches
php artisan optimize:clear

# Create new controller method
# Just edit the controller file manually

# Create Form Request
php artisan make:request RequestName

# Create new migration
php artisan make:migration migration_name

# Reset everything
php artisan migrate:fresh --seed
```

---

## 🐛 Troubleshooting

**Problem**: Routes not working  
**Solution**: `php artisan route:clear`

**Problem**: Views not updating  
**Solution**: `php artisan view:clear`

**Problem**: Database errors  
**Solution**: `php artisan migrate:fresh --seed`

**Problem**: CSS not loading  
**Solution**: `npm run build`

---

## 📚 Next Steps

1. Read `README-SIMS.md` for full documentation
2. Start implementing DashboardController
3. Move to StudentController CRUD
4. Test each feature as you build
5. Add validation and error handling
6. Polish the UI
7. Add reports and exports

---

**Good luck with your 1-week project! 🎓**
