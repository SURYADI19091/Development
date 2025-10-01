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
        Schema::table('village_officials', function (Blueprint $table) {
            $table->text('address')->nullable()->after('email');
            $table->integer('order')->default(1)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('village_officials', function (Blueprint $table) {
            $table->dropColumn(['address', 'order']);
        });
    }
};
