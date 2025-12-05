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
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->index('status');
            $table->index('year_level');
        });

        Schema::table('grade_import_batches', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['year_level']);
        });

        Schema::table('grade_import_batches', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
