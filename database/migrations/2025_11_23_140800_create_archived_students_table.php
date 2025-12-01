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
        Schema::create('archived_students', function (Blueprint $table) {
            $table->id();
            $table->json('student_data'); // Store complete student record as JSON
            $table->string('student_id', 20)->index();
            $table->string('name'); // For quick search
            $table->foreignId('program_id')->nullable()->constrained()->onDelete('set null');
            $table->string('year_level');
            $table->string('status');
            $table->string('archived_school_year'); // e.g., "2024-2025"
            $table->string('archived_semester'); // e.g., "1st Semester"
            $table->timestamp('archived_at');
            $table->foreignId('archived_by')->constrained('users')->onDelete('cascade');
            $table->text('archive_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_students');
    }
};
