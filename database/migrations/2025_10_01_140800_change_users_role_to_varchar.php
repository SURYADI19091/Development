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
        // First, we need to change the role column from ENUM to VARCHAR
        // This requires dropping the column and recreating it
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing ENUM role column
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Add the new VARCHAR role column with more options
            $table->string('role', 20)->default('user')->after('employee_id');
        });

        // Update any existing users with the old role values
        DB::statement("UPDATE users SET role = 'user' WHERE role = ''");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Restore the original ENUM column
            $table->enum('role', ['admin', 'secretary', 'village_head', 'staff', 'user'])->default('user')->after('employee_id');
        });
    }
};