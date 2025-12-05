# Test Students Reference

This document contains complete information for the 3 detailed test students created for development and testing purposes.

---

## TEST-2025-001: Juan Dela Cruz

### Personal Information
- **Full Name**: Dela Cruz, Juan Santos
- **Student ID**: TEST-2025-001
- **Birthdate**: January 15, 2005
- **Gender**: Male
- **Year Level**: 2nd Year
- **Program**: Bachelor in Industrial Technology (BIT)
- **Status**: Active
- **Attendance Type**: Regular

### Contact Information
- **Mobile**: 09171234567
- **Email**: juan.delacruz@student.bsu-bokod.edu.ph
- **Home Address**: Sitio Proper, Brgy. Tikey, Bokod, Benguet
- **Current Address**: Boarding House A, Poblacion, Bokod, Benguet

### Family Information
- **Mother**: Rosa Santos Dela Cruz
  - Contact: 09281234567
- **Father**: Roberto Dela Cruz
  - Contact: 09191234567
- **Emergency Contact**: Rosa Santos Dela Cruz (Mother)
  - Contact: 09281234567

### Academic Performance
- **GWA**: 2.22
- **Academic Standing**: Good
- **Enrollment Date**: December 2023 (approximately 2 years ago)

### Enrollment History
- **Total Enrollments**: 28 subjects
- **Completed**: 21 subjects with grades
- **Currently Enrolled**: 7 subjects (2025-2026, 1st Semester)

### Completed Semesters
1. **2023-2024, 1st Semester**: 4 subjects (1st Year)
2. **2023-2024, 2nd Semester**: 4 subjects (1st Year)
3. **2024-2025, 1st Semester**: 7 subjects (2nd Year)
4. **2024-2025, 2nd Semester**: 6 subjects (2nd Year)

### Student Portal Login
- **Email**: juan.delacruz@student.bsu-bokod.edu.ph
- **Password**: BSU-0012005

---

## TEST-2025-003: Pedro Santos

### Personal Information
- **Full Name**: Santos, Pedro Cruz
- **Student ID**: TEST-2025-003
- **Birthdate**: July 10, 2004
- **Gender**: Male
- **Year Level**: 4th Year
- **Program**: Bachelor in Industrial Technology (BIT)
- **Status**: Graduated
- **Student Type**: Candidate for graduation
- **Attendance Type**: Regular

### Contact Information
- **Mobile**: 09159876543
- **Email**: test.pedro.santos@student.bsu-bokod.edu.ph
- **Home Address**: Sitio Dalonot, Brgy. Ekip, Bokod, Benguet
- **Current Address**: Apartment 12, Poblacion, Bokod, Benguet

### Family Information
- **Mother**: Luz Cruz Santos
  - Contact: 09259876543
- **Father**: Manuel Santos
  - Contact: 09169876543
- **Emergency Contact**: Luz Cruz Santos (Mother)
  - Contact: 09259876543

### Academic Performance
- **Final GWA**: 2.13
- **Academic Standing**: Good
- **Enrollment Date**: December 2023 (approximately 2 years ago)

### Enrollment History
- **Total Enrollments**: 33 subjects
- **Completed**: 33 subjects with grades (ALL)
- **Currently Enrolled**: 0 subjects (GRADUATED)

### Completed Semesters
1. **2022-2023, 2nd Semester**: 4 subjects (1st Year)
2. **2023-2024, 1st Semester**: 7 subjects (2nd Year)
3. **2023-2024, 2nd Semester**: 6 subjects (2nd Year)
4. **2024-2025, 1st Semester**: 6 subjects (3rd Year)
5. **2024-2025, 2nd Semester**: 3 subjects (3rd Year)
6. **Additional 4th Year subjects**: 7 subjects (Completed all requirements)

### Student Portal Login
- **Email**: test.pedro.santos@student.bsu-bokod.edu.ph
- **Password**: BSU-0032004

---

## Grade Distribution Notes

All three students have realistic grade distributions based on the Philippine grading scale:
- **70%** of grades are Good (1.00-2.50)
- **20%** of grades are Passing but Lower (2.75-3.00)
- **8%** of grades are Conditional Pass (3.25-4.00)
- **2%** of grades are Failing (5.00)

This distribution creates realistic GWA values that can be used to test:
- Academic standing calculations
- Promotion/retention rules
- Irregular student scenarios
- Grade reporting features
- Dean's list qualification

---

## Testing Scenarios

These students can be used to test:

### Juan Dela Cruz (2nd Year, GWA 2.22)
- Standard progression through 2nd year
- Regular student with good standing
- Multiple semesters of enrollment history
- Completed both 1st and 2nd year
- Clean data with no current enrollments (between semesters)

### Pedro Santos (4th Year, GWA 2.13) - GRADUATED
- Completed all degree requirements
- Extensive enrollment history (4 years, all subjects completed)
- Multi-year academic progression from 1st to 4th year
- Graduated with good academic standing
- Perfect example of complete student lifecycle

---

## Database Seeders Used

1. **PastAcademicYearsSeeder**: Created academic years 2022-2023 through 2024-2025
2. **TestStudentAccountSeeder**: Created initial 5 test student accounts
3. **UpdateTestStudentsDataSeeder**: Populated detailed personal info and enrollment history for first 3 students
4. **FixMissingPortalAccountSeeder**: Created portal account for TEST-2025-003
5. **MakePedroGraduateSeeder**: Converted Pedro Santos to graduated status with all requirements completed
6. **FixJuanMissing1stSemesterSeeder**: Added Juan's missing 1st year 1st semester enrollments
7. **RemoveJuanDuplicateEnrollmentsSeeder**: Removed duplicate current enrollments
8. **RemoveMariaReyesSeeder**: Removed Maria Reyes (TEST-2025-002) from system

## Commands to Recreate Test Data

```bash
# Create past academic years
php artisan db:seed --class=PastAcademicYearsSeeder

# Create/update test students
php artisan db:seed --class=TestStudentAccountSeeder
php artisan db:seed --class=UpdateTestStudentsDataSeeder
php artisan db:seed --class=FixMissingPortalAccountSeeder

# Convert Pedro Santos to graduate
php artisan db:seed --class=MakePedroGraduateSeeder

# Fix Juan's data
php artisan db:seed --class=FixJuanMissing1stSemesterSeeder
php artisan db:seed --class=RemoveJuanDuplicateEnrollmentsSeeder

# Remove Maria Reyes
php artisan db:seed --class=RemoveMariaReyesSeeder
```

---

Last Updated: December 5, 2025
