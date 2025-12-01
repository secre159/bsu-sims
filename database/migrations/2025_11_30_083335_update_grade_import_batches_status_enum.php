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
        // Change status from enum to string to allow more flexibility
        DB::statement('ALTER TABLE grade_import_batches RENAME COLUMN status TO status_old');
        DB::statement('ALTER TABLE grade_import_batches ADD COLUMN status VARCHAR(255) DEFAULT "pending"');
        DB::statement('UPDATE grade_import_batches SET status = status_old');
        DB::statement('ALTER TABLE grade_import_batches DROP COLUMN status_old');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For safety, we won't reverse this migration
    }
};
