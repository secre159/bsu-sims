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
        Schema::table('students', function (Blueprint $table) {
            // Personal Information
            $table->string('maiden_name')->nullable()->after('middle_name');
            $table->date('date_of_birth')->nullable()->after('birthdate');
            $table->string('place_of_birth')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('ethnicity_tribal_affiliation')->nullable();
            
            // Contact Information
            $table->string('home_address')->nullable();
            $table->string('address_while_studying')->nullable();
            $table->string('email_address')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_contact_number')->nullable();
            $table->string('father_contact_number')->nullable();
            $table->string('emergency_contact_person')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_number')->nullable();
            
            // Academic Information
            $table->enum('student_type', ['Continuing', 'New/Returner', 'Candidate for graduation'])->nullable();
            $table->string('degree')->nullable();
            $table->string('major')->nullable();
            $table->string('section')->nullable();
            $table->enum('attendance_type', ['regular', 'irregular'])->nullable();
            $table->string('curriculum_used')->nullable();
            
            // Registration Information
            $table->integer('total_units_enrolled')->nullable();
            $table->enum('free_higher_education_benefit', ['yes', 'yes_with_contribution', 'no_optout', 'not_qualified'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'maiden_name', 'date_of_birth', 'place_of_birth', 'citizenship', 'ethnicity_tribal_affiliation',
                'home_address', 'address_while_studying', 'email_address', 'mother_name', 'mother_contact_number',
                'father_contact_number', 'emergency_contact_person', 'emergency_contact_relationship', 'emergency_contact_number',
                'student_type', 'degree', 'major', 'section', 'attendance_type', 'curriculum_used',
                'total_units_enrolled', 'free_higher_education_benefit'
            ]);
        });
    }
};
