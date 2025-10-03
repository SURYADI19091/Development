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
        Schema::table('locations', function (Blueprint $table) {
            $table->decimal('area_size', 15, 2)->nullable()->after('longitude')->comment('Area size in square meters');
            $table->json('area_coordinates')->nullable()->after('area_size')->comment('JSON coordinates for area boundary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['area_size', 'area_coordinates']);
        });
    }
};
