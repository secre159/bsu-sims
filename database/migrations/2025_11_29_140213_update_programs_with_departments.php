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
        // Get department IDs
        $departments = \DB::table('departments')->get();
        $deptMap = [];
        foreach ($departments as $dept) {
            $deptMap[$dept->code] = $dept->id;
        }

        // Update programs with their departments
        $programDepts = [
            'BIT' => 'CAT',          // College of Applied Technology
            'BSIT' => 'CAT',         // College of Applied Technology
            'BSEntrep' => 'CAT',     // Could be CAT (Arts & Tech includes business)
            'BPA' => 'CAT',          // Could be CAT
            'BTLEd' => 'CED',        // College of Education
            'BTVTEd' => 'CED',       // College of Education
            'BEEd' => 'CED',         // College of Education
            'BSEd' => 'CED',         // College of Education
            'BCAEd' => 'CED',        // College of Education
            'BSCrim' => 'CCJE',      // College of Criminal Justice and Education
        ];

        foreach ($programDepts as $progCode => $deptCode) {
            if (isset($deptMap[$deptCode])) {
                \DB::table('programs')
                    ->where('code', $progCode)
                    ->update(['department_id' => $deptMap[$deptCode]]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('programs')->update(['department_id' => null]);
    }
};
