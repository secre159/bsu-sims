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
        Schema::table('academic_years', function (Blueprint $table) {
            $table->date('registration_start_date')->nullable();
            $table->date('registration_end_date')->nullable();
            $table->date('add_drop_deadline')->nullable();
            $table->date('classes_start_date')->nullable();
            $table->date('classes_end_date')->nullable();
            $table->date('midterm_start_date')->nullable();
            $table->date('midterm_end_date')->nullable();
            $table->date('exam_start_date')->nullable();
            $table->date('exam_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropColumn([
                'registration_start_date',
                'registration_end_date',
                'add_drop_deadline',
                'classes_start_date',
                'classes_end_date',
                'midterm_start_date',
                'midterm_end_date',
                'exam_start_date',
                'exam_end_date',
            ]);
        });
    }
};
