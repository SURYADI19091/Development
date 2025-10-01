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
        Schema::create('village_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('village_name');
            $table->string('district');
            $table->string('regency');
            $table->string('province');
            $table->string('village_code', 50)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->decimal('area_size', 10, 2)->nullable()->comment('dalam hektar');
            $table->integer('total_population')->default(0);
            $table->integer('total_families')->default(0);
            $table->integer('male_population')->default(0);
            $table->integer('female_population')->default(0);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('altitude', 50)->nullable();
            $table->string('topography', 100)->nullable();
            $table->string('north_border')->nullable();
            $table->string('south_border')->nullable();
            $table->string('east_border')->nullable();
            $table->string('west_border')->nullable();
            $table->text('description')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_profiles');
    }
};