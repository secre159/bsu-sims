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
        Schema::create('grade_import_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_import_batch_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_id')->nullable()->constrained()->onDelete('set null');
            $table->string('student_id_number');
            $table->string('subject_code');
            $table->string('grade')->nullable()->comment('Stores numeric grades (1.00-5.00) or special grades (IP, INC)');
            $table->enum('status', ['pending', 'matched', 'error'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_import_records');
    }
};
