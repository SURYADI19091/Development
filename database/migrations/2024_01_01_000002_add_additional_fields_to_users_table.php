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
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id', 50)->unique()->nullable()->after('email');
            $table->enum('role', ['admin', 'secretary', 'village_head', 'staff', 'user'])->default('user')->after('employee_id');
            $table->string('phone', 20)->nullable()->after('role');
            $table->string('avatar')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_id', 'role', 'phone', 'avatar', 'is_active']);
        });
    }
};