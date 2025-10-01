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
            // Add status field if it doesn't exist
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('active')->after('is_active');
            }
            
            // Add authentication tracking fields
            if (!Schema::hasColumn('users', 'registered_at')) {
                $table->timestamp('registered_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('users', 'registered_ip')) {
                $table->string('registered_ip', 45)->nullable()->after('registered_at');
            }
            
            if (!Schema::hasColumn('users', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('registered_ip');
            }
            
            if (!Schema::hasColumn('users', 'password_changed_at')) {
                $table->timestamp('password_changed_at')->nullable()->after('user_agent');
            }
            
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('password_changed_at');
            }
            
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            }
            
            if (!Schema::hasColumn('users', 'login_attempts')) {
                $table->integer('login_attempts')->default(0)->after('last_login_ip');
            }
            
            if (!Schema::hasColumn('users', 'locked_until')) {
                $table->timestamp('locked_until')->nullable()->after('login_attempts');
            }
            
            // Add additional profile fields
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('avatar');
            }
            
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('address');
            }
            
            if (!Schema::hasColumn('users', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('locked_until');
            }
            
            if (!Schema::hasColumn('users', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'status', 'registered_at', 'registered_ip', 'user_agent', 
                'password_changed_at', 'last_login_at', 'last_login_ip', 
                'login_attempts', 'locked_until', 'address', 'date_of_birth',
                'created_by', 'updated_by'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
