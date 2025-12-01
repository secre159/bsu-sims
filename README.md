# BSU-Bokod Student Information Management System (SIMS)

A comprehensive web-based student information management system built for Benguet State University - Bokod Campus using Laravel 11 and modern web technologies.

## Features

### 🎓 Academic Management
- **Student Records Management** - Complete student information with photo uploads
- **Program & Department Management** - Organize students by programs and departments
- **Subject Management** - Auto-sync subjects with their respective programs
- **4-Year Program Structure** - Support for 1st to 4th year academic progression

### 📊 Grade Management
- **Grade Import Workflow** - Import grades from CSV/Excel files
- **Grade Approval System** - Admin-only approval workflow for grades
- **GPA Calculation** - Automatic GPA computation with configurable grading scales
- **Academic Standing** - Track student performance (Good Standing, Warning, Probation, Dismissed)

### 🔄 Academic Progression
- **Semester Transition** - Automated semester-to-semester student progression
- **Academic Year Management** - Manage active and archived academic years
- **Standing Calculation** - Automatic academic standing updates based on GPA

### 📦 Archive System
- **Student Archiving** - Create snapshots of student data by school year/semester
- **Historical Records** - Maintain permanent records for compliance and audits
- **Restore Functionality** - Restore archived students when needed
- **Collapsible UI** - Clean, modern interface with toggleable information cards

### 🔐 Security & Administration
- **Role-Based Access** - Admin and User roles with appropriate permissions
- **Activity Logging** - Track all system actions with user attribution
- **Grade Approval** - Restrict grade approval to administrators only

### 🎨 Modern UI/UX
- **Green Design System** - Custom green-themed color palette
- **Responsive Design** - Mobile-friendly interface
- **Smooth Animations** - CSS transitions and Alpine.js interactivity
- **Tab Navigation** - Active tab highlighting with animated underlines

## Tech Stack

- **Framework**: Laravel 11
- **Frontend**: 
  - Blade Templates
  - Tailwind CSS
  - Alpine.js
  - Vite
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Breeze
- **Notifications**: Toastify.js

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- MySQL/MariaDB

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/secre159/bsu-sims.git
   cd bsu-sims
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your database**
   Edit `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bsu_sims
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Create storage link**
   ```bash
   php artisan storage:link
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

10. **Access the application**
    - Open your browser and navigate to `http://localhost:8000`
    - Default admin credentials (if seeded):
      - Email: admin@bsu.edu.ph
      - Password: password

## Usage

### Grade Import
1. Navigate to **Grade Approvals**
2. Click **Import Grades**
3. Download the CSV template
4. Fill in student grades following the template format
5. Upload the completed CSV file
6. Review imported grades
7. Submit for approval (Admin only)

### Semester Transition
1. Go to **Academic Years**
2. Select the active academic year
3. Click **Semester Transition**
4. Choose target semester
5. Review transition preview
6. Execute transition to promote students

### Archive Students
1. Navigate to **Archives**
2. Click **Archive School Year**
3. Review current student statistics
4. Enter school year and semester
5. Optionally add archive reason
6. Choose whether to delete students after archiving
7. Create archive

## Project Structure

```
bsu-sims/
├── app/
│   ├── Http/Controllers/    # Application controllers
│   ├── Models/              # Eloquent models
│   └── Services/            # Business logic services
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── css/                # Custom styles
│   ├── js/                 # JavaScript files
│   └── views/              # Blade templates
├── routes/
│   └── web.php             # Web routes
└── public/
    └── build/              # Compiled assets
```

## Key Services

- **GpaCalculationService** - Handles GPA computation and grading scale management
- **AcademicStandingService** - Calculates and updates student academic standings
- **SemesterTransitionService** - Manages semester-to-semester student progression

## Contributing

This is an internal project for BSU-Bokod. If you'd like to contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is proprietary software developed for Benguet State University - Bokod Campus.

## Contact

For questions or support, please contact the BSU-Bokod IT Department.

---

**Developed for Benguet State University - Bokod Campus**
