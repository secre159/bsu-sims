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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['Enrolled', 'Completed', 'Dropped', 'Failed'])->default('Enrolled');
            $table->decimal('grade', 5, 2)->nullable(); // e.g., 1.00, 1.25, 1.50, etc.
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Prevent duplicate enrollments
            $table->unique(['student_id', 'subject_id', 'academic_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
