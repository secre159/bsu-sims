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
            $table->string('grade', 10)->nullable()->change();
        });

        Schema::table('grade_histories', function (Blueprint $table) {
            $table->string('old_grade', 10)->nullable()->change();
            $table->string('new_grade', 10)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->decimal('grade', 5, 2)->nullable()->change();
        });

        Schema::table('grade_histories', function (Blueprint $table) {
            $table->decimal('old_grade', 5, 2)->nullable()->change();
            $table->decimal('new_grade', 5, 2)->change();
        });
    }
};
