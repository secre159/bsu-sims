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
            // Only add new columns that don't exist yet
            $table->timestamp('submission_date')->nullable()->after('status');
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('set null')->after('submission_date');
            $table->timestamp('approved_at')->nullable()->after('approver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['submission_date', 'approver_id', 'approved_at']);
        });
    }
};
