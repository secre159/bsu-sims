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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20); // e.g., CS101
            $table->string('name'); // e.g., Introduction to Programming
            $table->text('description')->nullable();
            $table->integer('units')->default(3); // Credit units
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->enum('year_level', ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year']);
            $table->enum('semester', ['1st Semester', '2nd Semester', 'Summer']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Unique constraint on code + program combination
            $table->unique(['code', 'program_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
