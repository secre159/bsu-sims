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
        Schema::table('grade_import_batches', function (Blueprint $table) {
            $table->json('column_mapping')->nullable()->comment('Maps CSV columns: {"student_id": 0, "subject_code": 1, "grade": 2}');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grade_import_batches', function (Blueprint $table) {
            $table->dropColumn('column_mapping');
        });
    }
};
