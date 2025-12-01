<?php

namespace Tests;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use App\Models\GradeImportBatch;
use App\Models\GradeHistory;
use App\Models\Notification;
use App\Services\GpaCalculationService;

class GradingSystemTest extends TestCase
{
    protected GpaCalculationService $gpaService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gpaService = app(GpaCalculationService::class);
    }

    /**
     * Test models are created successfully
     */
    public function test_models_exist()
    {
        $this->assertTrue(class_exists('App\Models\GradeImportBatch'));
        $this->assertTrue(class_exists('App\Models\GradeHistory'));
        $this->assertTrue(class_exists('App\Models\Notification'));
        $this->assertTrue(method_exists(Notification::class, 'user'));
        $this->assertTrue(method_exists(GradeImportBatch::class, 'gradeImportRecords'));
    }

    /**
     * Test GPA calculation
     */
    public function test_gpa_calculation()
    {
        // Create test data
        $department = Department::factory()->create();
        $academicYear = AcademicYear::factory()->create(['is_current' => true]);
        $student = Student::factory()->create();
        
        // Create subject and enrollment with grade
        $subject = Subject::factory()->create(['department_id' => $department->id, 'units' => 3]);
        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'academic_year_id' => $academicYear->id,
            'grade' => 85.00, // Should be 3.0 grade points
        ]);

        // Calculate GPA
        $gpa_data = $this->gpaService->calculateStudentGpa($student, $academicYear);
        
        $this->assertIsArray($gpa_data);
        $this->assertArrayHasKey('gpa', $gpa_data);
        $this->assertArrayHasKey('standing', $gpa_data);
        $this->assertEquals(3.0, $gpa_data['gpa']);
        $this->assertEquals('Active', $gpa_data['standing']);
    }

    /**
     * Test grade history tracking
     */
    public function test_grade_history_tracking()
    {
        $user = User::factory()->create();
        $enrollment = Enrollment::factory()->create(['grade' => 80.00]);

        // Create grade history
        $history = GradeHistory::create([
            'enrollment_id' => $enrollment->id,
            'user_id' => $user->id,
            'old_grade' => 80.00,
            'new_grade' => 85.00,
            'reason' => 'Test modification',
        ]);

        $this->assertNotNull($history->id);
        $this->assertEquals($enrollment->id, $history->enrollment_id);
        $this->assertEquals(80.00, $history->old_grade);
        $this->assertEquals(85.00, $history->new_grade);
        $this->assertEquals('Test modification', $history->reason);
    }

    /**
     * Test notification system
     */
    public function test_notification_creation()
    {
        $user = User::factory()->create();

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'grade_batch_uploaded',
            'title' => 'Grade Batch Uploaded',
            'message' => 'Test batch upload',
            'action_url' => '/admin/grade-approvals',
        ]);

        $this->assertNotNull($notification->id);
        $this->assertEquals('grade_batch_uploaded', $notification->type);
        $this->assertFalse($notification->read);

        // Mark as read
        $notification->markAsRead();
        $this->assertTrue($notification->fresh()->read);
        $this->assertNotNull($notification->fresh()->read_at);
    }

    /**
     * Test irregular student standing
     */
    public function test_irregular_student_standing()
    {
        $department = Department::factory()->create();
        $academicYear = AcademicYear::factory()->create(['is_current' => true]);
        $student = Student::factory()->create();
        
        // Create enrollment with failing grade
        $subject = Subject::factory()->create(['department_id' => $department->id, 'units' => 3]);
        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'academic_year_id' => $academicYear->id,
            'grade' => 55.00, // Should be 0.0 grade points (failing)
        ]);

        // Calculate GPA
        $gpa_data = $this->gpaService->calculateStudentGpa($student, $academicYear);
        
        $this->assertEquals('Irregular', $gpa_data['standing']);
        $this->assertEquals(0.0, $gpa_data['gpa']);
    }

    /**
     * Test Grade import batch model
     */
    public function test_grade_import_batch_creation()
    {
        $chairperson = User::factory()->create(['role' => 'chairperson']);

        $batch = GradeImportBatch::create([
            'chairperson_id' => $chairperson->id,
            'file_name' => 'test_grades.xlsx',
            'total_records' => 50,
            'success_count' => 48,
            'error_count' => 2,
            'status' => 'pending',
        ]);

        $this->assertNotNull($batch->id);
        $this->assertEquals('test_grades.xlsx', $batch->file_name);
        $this->assertEquals(48, $batch->success_count);
        $this->assertEquals('pending', $batch->status);
        $this->assertEquals($chairperson->id, $batch->chairperson_id);
    }

    /**
     * Test user relationships
     */
    public function test_user_grade_relationships()
    {
        $user = User::factory()->create();
        
        // Test notifications relationship
        Notification::create([
            'user_id' => $user->id,
            'type' => 'test',
            'title' => 'Test',
            'message' => 'Test message',
        ]);

        $notifications = $user->notifications;
        $this->assertCount(1, $notifications);
        $this->assertEquals('test', $notifications[0]->type);
    }

    /**
     * Test Dean's Lister standing
     */
    public function test_deans_lister_standing()
    {
        $department = Department::factory()->create();
        $academicYear = AcademicYear::factory()->create(['is_current' => true]);
        $student = Student::factory()->create();
        
        // Create enrollment with high grade
        $subject = Subject::factory()->create(['department_id' => $department->id, 'units' => 3]);
        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'academic_year_id' => $academicYear->id,
            'grade' => 95.00, // Should be 4.0 grade points
        ]);

        // Calculate GPA
        $gpa_data = $this->gpaService->calculateStudentGpa($student, $academicYear);
        
        $this->assertEquals('Dean\'s Lister', $gpa_data['standing']);
        $this->assertEquals(4.0, $gpa_data['gpa']);
    }
}
