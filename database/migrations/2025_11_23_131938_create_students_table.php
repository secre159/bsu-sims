<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 20)->unique(); // Student ID number
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix', 10)->nullable(); // Jr., Sr., III, etc.
            $table->date('birthdate');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('contact_number', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('program_id')->constrained()->onDelete('restrict');
            $table->enum('year_level', ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year']);
            $table->enum('status', ['Active', 'Graduated', 'Dropped', 'On Leave', 'Transferred'])->default('Active');
            $table->string('photo_path')->nullable();
            $table->date('enrollment_date')->nullable();
            // Store academic_year_id without a DB-level foreign key here.
            // The related AcademicYear table is created in a later migration.
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
