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
        Schema::table('village_profiles', function (Blueprint $table) {
            $table->text('address')->nullable()->after('description');
            $table->string('phone', 20)->nullable()->after('address');
            $table->string('email')->nullable()->after('phone');
            $table->string('website')->nullable()->after('email');
            $table->integer('total_rw')->default(0)->after('website');
            $table->integer('total_rt')->default(0)->after('total_rw');
            $table->text('history')->nullable()->after('total_rt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('village_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'phone', 
                'email',
                'website',
                'total_rw',
                'total_rt',
                'history'
            ]);
        });
    }
};
