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
        Schema::create('academic_standing_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->string('from_standing')->nullable();
            $table->string('to_standing');
            $table->decimal('gwa_calculated', 3, 2)->nullable();
            $table->integer('failed_subjects_count')->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->index(['student_id', 'academic_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_standing_logs');
    }
};
