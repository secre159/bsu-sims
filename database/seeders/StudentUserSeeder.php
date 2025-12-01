<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentUser;
use App\Mail\StudentPasswordMail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StudentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $sendEmails = false; // Set to true to send actual emails

        echo "\n=== Creating StudentUser Accounts ===\n";
        echo "Total students: " . $students->count() . "\n\n";

        $successCount = 0;
        $skipCount = 0;

        foreach ($students as $student) {
            // Skip if already has a user account
            if ($student->id && StudentUser::where('student_id', $student->id)->exists()) {
                echo "[SKIP] {$student->student_id} - Already has account\n";
                $skipCount++;
                continue;
            }

            // Generate temporary password
            $temporaryPassword = Str::random(12);

            try {
                // Create StudentUser account
                $studentUser = StudentUser::create([
                    'student_id' => $student->id,
                    'email' => $student->email,
                    'password' => Hash::make($temporaryPassword),
                ]);

                echo "[✓] Created account for {$student->student_id}\n";

                // Send email with credentials (optional)
                if ($sendEmails && $student->email) {
                    try {
                        Mail::to($student->email)->send(
                            new StudentPasswordMail(
                                $studentUser,
                                $temporaryPassword,
                                $student->first_name . ' ' . $student->last_name
                            )
                        );
                        echo "    └─ Email sent to {$student->email}\n";
                    } catch (\Exception $e) {
                        echo "    └─ ⚠️ Email failed: " . $e->getMessage() . "\n";
                    }
                }

                $successCount++;
            } catch (\Exception $e) {
                echo "[ERROR] {$student->student_id}: " . $e->getMessage() . "\n";
            }
        }

        echo "\n=== Summary ===\n";
        echo "Created: {$successCount}\n";
        echo "Skipped: {$skipCount}\n";
        echo "Total: " . ($successCount + $skipCount) . "\n";
        echo "\n📧 To send emails, set \$sendEmails = true\n";
        echo "⚙️  Configure MAIL_* in .env first\n";
        echo "💡 Test credentials: Student ID + temporary password\n\n";
    }
}
