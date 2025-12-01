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
        Schema::create('grade_import_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chairperson_id')->constrained('users')->onDelete('cascade');
            $table->string('file_name');
            $table->integer('total_records');
            $table->integer('success_count');
            $table->integer('error_count');
            $table->enum('status', ['processing', 'pending', 'ready', 'submitted', 'approved', 'rejected'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_import_batches');
    }
};
