# BSU-Bokod Student Information Management System (SIMS)

## Project Overview
A web-based Student Information Management System built with Laravel and Blade for BSU-Bokod Campus. This system helps manage student records, programs, and generate reports for educational purposes.

## Features Implemented (Skeleton)

### Core Features
- ✅ **Dashboard** - Overview with statistics and quick actions
- ✅ **Student Management** - CRUD operations for student records
- ✅ **Program Management** - Manage academic programs
- ✅ **Reports** - Generate various student reports
- ✅ **Authentication** - User login/logout system
- ✅ **Academic Year Tracking** - Track students by academic year

### Database Structure
- **students** - Student personal and academic information
- **programs** - Academic programs (BSIT, BSEd, etc.)
- **academic_years** - School year and semester tracking
- **student_history** - Audit trail of student record changes
- **users** - System users (admin/staff)

## Technology Stack
- **Framework**: Laravel 11
- **Frontend**: Blade Templates + Tailwind CSS
- **Authentication**: Laravel Breeze
- **Database**: SQLite (default, can be changed to MySQL)
- **PHP**: 8.2+
- **Node.js**: For asset compilation

## Installation & Setup

### 1. Navigate to Project Directory
```powershell
cd "C:\Users\Axl Chan\Desktop\bsu-sims"
```

### 2. Install Dependencies (if needed)
```powershell
composer install
npm install
```

### 3. Set Up Environment
Copy `.env.example` to `.env` (if not already done):
```powershell
copy .env.example .env
```

Generate application key:
```powershell
php artisan key:generate
```

### 4. Run Migrations and Seeders
```powershell
php artisan migrate:fresh --seed
```

This will create:
- All database tables
- Default admin user: `admin@bsu-bokod.edu.ph` / `password`
- Sample programs (BSIT, BSEd, BSAgri, etc.)
- Sample academic year (2024-2025)

### 5. Build Assets
```powershell
npm run build
```

### 6. Start Development Server
```powershell
php artisan serve
```

Visit: http://127.0.0.1:8000

### Default Login Credentials
- **Email**: admin@bsu-bokod.edu.ph
- **Password**: password

## Project Structure

```
bsu-sims/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php      # Dashboard logic
│   │   ├── StudentController.php        # Student CRUD operations
│   │   ├── ProgramController.php        # Program management
│   │   ├── ReportController.php         # Report generation
│   │   └── AcademicYearController.php   # Academic year management
│   └── Models/
│       ├── Student.php                  # Student model with relationships
│       ├── Program.php                  # Program model
│       ├── AcademicYear.php            # Academic year model
│       └── StudentHistory.php           # Audit trail model
├── database/
│   ├── migrations/                      # Database schema definitions
│   └── seeders/                         # Sample data seeders
├── resources/
│   └── views/
│       ├── dashboard.blade.php          # Dashboard view
│       ├── students/                    # Student views
│       │   ├── index.blade.php         # Student list
│       │   ├── create.blade.php        # Add student form
│       │   ├── edit.blade.php          # Edit student form
│       │   └── show.blade.php          # Student details
│       ├── programs/                    # Program views
│       │   └── index.blade.php         # Program list
│       └── reports/                     # Report views
│           └── index.blade.php         # Reports dashboard
└── routes/
    └── web.php                          # Application routes
```

## Next Steps (Implementation Tasks)

### High Priority
1. **Implement DashboardController**
   - Calculate statistics (total students, active, graduated, programs)
   - Fetch recent students
   - Pass data to dashboard view

2. **Implement StudentController**
   - `index()` - List students with search/filter
   - `create()` - Show add student form
   - `store()` - Save new student
   - `show()` - Display student details
   - `edit()` - Show edit form
   - `update()` - Update student
   - `destroy()` - Delete/archive student

3. **Complete Student Views**
   - Populate program dropdown in create/edit forms
   - Add validation error displays
   - Implement pagination in index
   - Complete edit form with student data

4. **Implement ProgramController**
   - Basic CRUD for programs
   - Show student count per program

5. **Implement ReportController**
   - `studentsList()` - Generate student master list
   - `programsList()` - Students by program
   - `yearLevelsList()` - Students by year level
   - `exportStudents()` - Export to Excel

### Medium Priority
6. **Add Form Validation**
   - Create Form Request classes
   - Add validation rules
   - Display validation errors in views

7. **Implement Search & Filter**
   - Search by student ID, name
   - Filter by program, year level, status
   - Add pagination

8. **Add PDF/Excel Export**
   - Install `laravel-excel` package
   - Install `dompdf` package
   - Implement export methods

9. **Student Photo Upload**
   - Implement file upload in create/edit
   - Store photos in `storage/app/public/photos`
   - Create symlink: `php artisan storage:link`

### Low Priority
10. **Student History/Audit Trail**
    - Log changes when student records are modified
    - Display history in student detail view

11. **Bulk Operations**
    - Import students from CSV/Excel
    - Bulk update year levels
    - Mass status changes

12. **Advanced Features**
    - Academic year management UI
    - User role management
    - Email notifications
    - Dashboard charts/graphs

## Database Schema Details

### Students Table Fields
- `student_id` - Unique student ID number
- `last_name`, `first_name`, `middle_name`, `suffix` - Name fields
- `birthdate` - Date of birth
- `gender` - Male/Female/Other
- `contact_number`, `email` - Contact information
- `address` - Full address
- `program_id` - Foreign key to programs
- `year_level` - 1st Year, 2nd Year, etc.
- `status` - Active, Graduated, Dropped, On Leave, Transferred
- `photo_path` - Path to student photo
- `enrollment_date` - When student enrolled
- `academic_year_id` - Current academic year
- `notes` - Additional notes

### Programs Table Fields
- `code` - Program code (BSIT, BSEd, etc.)
- `name` - Full program name
- `description` - Program description
- `is_active` - Active status

## Common Commands

### Development
```powershell
# Start development server
php artisan serve

# Watch for asset changes
npm run dev

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Database
```powershell
# Run migrations
php artisan migrate

# Reset database and reseed
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name

# Create new seeder
php artisan make:seeder NameSeeder
```

### Code Generation
```powershell
# Create controller
php artisan make:controller NameController

# Create model
php artisan make:model ModelName

# Create model with migration
php artisan make:model ModelName -m
```

## Notes
- This is an educational project for learning purposes
- All sensitive data should be properly secured in production
- Change default passwords before deploying
- Consider using MySQL instead of SQLite for production
- Add proper backup procedures for production use

## Support
For issues or questions about this skeleton, refer to:
- Laravel Documentation: https://laravel.com/docs
- Blade Templates: https://laravel.com/docs/blade
- Tailwind CSS: https://tailwindcss.com/docs

---

**Created for**: BSU-Bokod Campus  
**Purpose**: Educational - Student Information Management  
**Timeline**: 1-week MVP project
