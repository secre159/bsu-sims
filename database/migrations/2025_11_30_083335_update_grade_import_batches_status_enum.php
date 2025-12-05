<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change status from enum to string to allow more flexibility.
        // On fresh installs this table is empty, so we can safely drop the
        // enum column and recreate it as a VARCHAR without data migration.
        Schema::table('grade_import_batches', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('grade_import_batches', function (Blueprint $table) {
            $table->string('status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For safety, we won't reverse this migration
    }
};
