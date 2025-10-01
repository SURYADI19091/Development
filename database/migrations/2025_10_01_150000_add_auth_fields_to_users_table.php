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
            // User status and role enhancements
            $table->enum('status', ['pending', 'active', 'inactive', 'banned', 'suspended'])
                  ->default('pending')
                  ->after('role')
                  ->comment('User account status');

            // Registration tracking
            $table->timestamp('registered_at')->nullable()->after('status');
            $table->string('registered_ip', 45)->nullable()->after('registered_at');
            $table->text('user_agent')->nullable()->after('registered_ip');

            // Password management
            $table->timestamp('password_changed_at')->nullable()->after('password');
            
            // Login tracking
            $table->timestamp('last_login_at')->nullable()->after('user_agent');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->unsignedTinyInteger('login_attempts')->default(0)->after('last_login_ip');
            $table->timestamp('locked_until')->nullable()->after('login_attempts');

            // Add indexes for performance
            $table->index(['status', 'role']);
            $table->index(['email', 'status']);
            $table->index('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status', 'role']);
            $table->dropIndex(['email', 'status']);
            $table->dropIndex(['last_login_at']);
            
            $table->dropColumn([
                'status',
                'registered_at',
                'registered_ip',
                'user_agent',
                'password_changed_at',
                'last_login_at',
                'last_login_ip',
                'login_attempts',
                'locked_until',
            ]);
        });
    }
};