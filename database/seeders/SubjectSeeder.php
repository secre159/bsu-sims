<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Program;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $programs = Program::all();
        $subjectIdMap = [];
        
        // BSIT - BS Information Technology
        $bsit = $programs->where('code', 'BSIT')->first();
        if ($bsit) {
            $subjects = [
                // 1st Year
                ['code' => 'CS101', 'name' => 'Introduction to Computing', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'PROG101', 'name' => 'Computer Programming 1', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'MATH111', 'name' => 'Discrete Mathematics', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'PROG102', 'name' => 'Computer Programming 2', 'units' => 3, 'year' => '1st Year', 'sem' => '2nd Semester'],
                ['code' => 'WEB101', 'name' => 'Web Development', 'units' => 3, 'year' => '1st Year', 'sem' => '2nd Semester'],
                ['code' => 'DATA101', 'name' => 'Data Structures', 'units' => 3, 'year' => '1st Year', 'sem' => '2nd Semester'],
                
                // 2nd Year
                ['code' => 'DB201', 'name' => 'Database Management Systems', 'units' => 3, 'year' => '2nd Year', 'sem' => '1st Semester'],
                ['code' => 'NET201', 'name' => 'Networking Fundamentals', 'units' => 3, 'year' => '2nd Year', 'sem' => '1st Semester'],
                ['code' => 'OOP201', 'name' => 'Object-Oriented Programming', 'units' => 3, 'year' => '2nd Year', 'sem' => '1st Semester'],
                ['code' => 'SOFT202', 'name' => 'Software Engineering', 'units' => 3, 'year' => '2nd Year', 'sem' => '2nd Semester'],
                
                // 3rd Year
                ['code' => 'OS301', 'name' => 'Operating Systems', 'units' => 3, 'year' => '3rd Year', 'sem' => '1st Semester'],
                ['code' => 'SEC301', 'name' => 'Information Security', 'units' => 3, 'year' => '3rd Year', 'sem' => '1st Semester'],
                ['code' => 'CAPSTONE302', 'name' => 'Capstone Project 1', 'units' => 3, 'year' => '3rd Year', 'sem' => '2nd Semester'],
                
                // 4th Year
                ['code' => 'CAPSTONE401', 'name' => 'Capstone Project 2', 'units' => 3, 'year' => '4th Year', 'sem' => '1st Semester'],
                ['code' => 'OJT402', 'name' => 'On-the-Job Training', 'units' => 6, 'year' => '4th Year', 'sem' => '2nd Semester'],
            ];
            
            foreach ($subjects as $subject) {
                $created = Subject::create([
                    'code' => $subject['code'],
                    'name' => $subject['name'],
                    'units' => $subject['units'],
                    'program_id' => $bsit->id,
                    'year_level' => $subject['year'],
                    'semester' => $subject['sem'],
                    'is_active' => true,
                ]);
                $subjectIdMap['BSIT'][$subject['code']] = $created->id;
            }
            
            // Add prerequisites
            if (isset($subjectIdMap['BSIT'])) {
                Subject::where('code', 'PROG102')->update(['prerequisite_subject_ids' => [$subjectIdMap['BSIT']['PROG101']]]);
                Subject::where('code', 'OOP201')->update(['prerequisite_subject_ids' => [$subjectIdMap['BSIT']['PROG102']]]);
            }
        }
        
        // BSEd - Bachelor of Secondary Education
        $bsed = $programs->where('code', 'BSEd')->first();
        if ($bsed) {
            $subjects = [
                // 1st Year
                ['code' => 'ED101', 'name' => 'The Teaching Profession', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'PSY101', 'name' => 'Child and Adolescent Development', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'FIL101', 'name' => 'Komunikasyon sa Akademikong Filipino', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'ED102', 'name' => 'The Teacher and the School Curriculum', 'units' => 3, 'year' => '1st Year', 'sem' => '2nd Semester'],
                
                // 2nd Year
                ['code' => 'ED201', 'name' => 'Assessment in Learning 1', 'units' => 3, 'year' => '2nd Year', 'sem' => '1st Semester'],
                ['code' => 'TECH201', 'name' => 'Technology for Teaching and Learning', 'units' => 3, 'year' => '2nd Year', 'sem' => '1st Semester'],
                ['code' => 'ED203', 'name' => 'Assessment in Learning 2', 'units' => 3, 'year' => '2nd Year', 'sem' => '2nd Semester'],
                
                // 3rd Year
                ['code' => 'ED301', 'name' => 'The Teacher and the Community', 'units' => 3, 'year' => '3rd Year', 'sem' => '1st Semester'],
                ['code' => 'FIELD301', 'name' => 'Field Study', 'units' => 3, 'year' => '3rd Year', 'sem' => '2nd Semester'],
                
                // 4th Year
                ['code' => 'TEACH401', 'name' => 'Teaching Internship', 'units' => 6, 'year' => '4th Year', 'sem' => '1st Semester'],
            ];
            
            foreach ($subjects as $subject) {
                Subject::create([
                    'code' => $subject['code'],
                    'name' => $subject['name'],
                    'units' => $subject['units'],
                    'program_id' => $bsed->id,
                    'year_level' => $subject['year'],
                    'semester' => $subject['sem'],
                    'is_active' => true,
                ]);
            }
        }
        
        // BEEd - Bachelor of Elementary Education
        $beed = $programs->where('code', 'BEEd')->first();
        if ($beed) {
            $subjects = [
                // 1st Year
                ['code' => 'ELED101', 'name' => 'The Teaching Profession', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'ELED102', 'name' => 'Facilitating Learner-Centered Teaching', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'ELED103', 'name' => 'Foundation of Special and Inclusive Education', 'units' => 3, 'year' => '1st Year', 'sem' => '2nd Semester'],
                
                // 2nd Year
                ['code' => 'ELED201', 'name' => 'Teaching Arts in the Elementary Grades', 'units' => 3, 'year' => '2nd Year', 'sem' => '1st Semester'],
                ['code' => 'ELED202', 'name' => 'Teaching English in the Elementary Grades', 'units' => 3, 'year' => '2nd Year', 'sem' => '2nd Semester'],
                
                // 3rd Year
                ['code' => 'ELED301', 'name' => 'Teaching Mathematics in the Elementary Grades', 'units' => 3, 'year' => '3rd Year', 'sem' => '1st Semester'],
                ['code' => 'ELED302', 'name' => 'Teaching Science in the Elementary Grades', 'units' => 3, 'year' => '3rd Year', 'sem' => '2nd Semester'],
                
                // 4th Year
                ['code' => 'ELED401', 'name' => 'Teaching Internship', 'units' => 6, 'year' => '4th Year', 'sem' => '1st Semester'],
            ];
            
            foreach ($subjects as $subject) {
                Subject::create([
                    'code' => $subject['code'],
                    'name' => $subject['name'],
                    'units' => $subject['units'],
                    'program_id' => $beed->id,
                    'year_level' => $subject['year'],
                    'semester' => $subject['sem'],
                    'is_active' => true,
                ]);
            }
        }
        
        // BSEntrep - BS Entrepreneurship
        $bsentrep = $programs->where('code', 'BSEntrep')->first();
        if ($bsentrep) {
            $subjects = [
                // 1st Year
                ['code' => 'ENTREP101', 'name' => 'Introduction to Entrepreneurship', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'ACCTG101', 'name' => 'Fundamentals of Accounting', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'ENTREP102', 'name' => 'Business Planning', 'units' => 3, 'year' => '1st Year', 'sem' => '2nd Semester'],
                
                // 2nd Year
                ['code' => 'ENTREP201', 'name' => 'Marketing Management', 'units' => 3, 'year' => '2nd Year', 'sem' => '1st Semester'],
                ['code' => 'ENTREP202', 'name' => 'Financial Management', 'units' => 3, 'year' => '2nd Year', 'sem' => '2nd Semester'],
                
                // 3rd Year
                ['code' => 'ENTREP301', 'name' => 'Business Operations', 'units' => 3, 'year' => '3rd Year', 'sem' => '1st Semester'],
                ['code' => 'ENTREP302', 'name' => 'Social Entrepreneurship', 'units' => 3, 'year' => '3rd Year', 'sem' => '2nd Semester'],
                
                // 4th Year
                ['code' => 'ENTREP401', 'name' => 'Business Practicum', 'units' => 6, 'year' => '4th Year', 'sem' => '1st Semester'],
            ];
            
            foreach ($subjects as $subject) {
                Subject::create([
                    'code' => $subject['code'],
                    'name' => $subject['name'],
                    'units' => $subject['units'],
                    'program_id' => $bsentrep->id,
                    'year_level' => $subject['year'],
                    'semester' => $subject['sem'],
                    'is_active' => true,
                ]);
            }
        }
        
        // BIT - Bachelor in Industrial Technology
        $bit = $programs->where('code', 'BIT')->first();
        if ($bit) {
            $subjects = [
                // 1st Year
                ['code' => 'BIT101', 'name' => 'Introduction to ICT', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'BIT102', 'name' => 'Technical Drawing', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'BIT103', 'name' => 'Occupational Safety and Health', 'units' => 3, 'year' => '1st Year', 'sem' => '2nd Semester'],
                
                // 2nd Year
                ['code' => 'BIT201', 'name' => 'Cooperative Management', 'units' => 3, 'year' => '2nd Year', 'sem' => '1st Semester'],
                ['code' => 'BIT202', 'name' => 'Operations Management', 'units' => 3, 'year' => '2nd Year', 'sem' => '2nd Semester'],
                
                // 3rd Year
                ['code' => 'BIT301', 'name' => 'Technical Research', 'units' => 3, 'year' => '3rd Year', 'sem' => '1st Semester'],
                ['code' => 'BIT302', 'name' => 'Industrial Immersion', 'units' => 6, 'year' => '3rd Year', 'sem' => '2nd Semester'],
                
                // 4th Year
                ['code' => 'BIT401', 'name' => 'On-the-Job Training', 'units' => 12, 'year' => '4th Year', 'sem' => '1st Semester'],
            ];
            
            foreach ($subjects as $subject) {
                Subject::create([
                    'code' => $subject['code'],
                    'name' => $subject['name'],
                    'units' => $subject['units'],
                    'program_id' => $bit->id,
                    'year_level' => $subject['year'],
                    'semester' => $subject['sem'],
                    'is_active' => true,
                ]);
            }
        }
        
        // BSCrim - BS Criminology
        $bscrim = $programs->where('code', 'BSCrim')->first();
        if ($bscrim) {
            $subjects = [
                ['code' => 'CRIM101', 'name' => 'Introduction to Criminology', 'units' => 3, 'year' => '1st Year', 'sem' => '1st Semester'],
                ['code' => 'CRIM102', 'name' => 'Criminal Law', 'units' => 3, 'year' => '1st Year', 'sem' => '2nd Semester'],
                ['code' => 'CRIM201', 'name' => 'Criminal Investigation', 'units' => 3, 'year' => '2nd Year', 'sem' => '1st Semester'],
            ];
            
            foreach ($subjects as $subject) {
                Subject::create([
                    'code' => $subject['code'],
                    'name' => $subject['name'],
                    'units' => $subject['units'],
                    'program_id' => $bscrim->id,
                    'year_level' => $subject['year'],
                    'semester' => $subject['sem'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
