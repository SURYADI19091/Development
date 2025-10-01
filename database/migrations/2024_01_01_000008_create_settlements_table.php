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
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('Village')->comment('Settlement type: Village, Urban, etc.');
            $table->string('code')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('hamlet_name');
            $table->string('hamlet_leader');
            $table->string('neighborhood_name'); // RT name
            $table->string('neighborhood_number'); // RT number
            $table->string('community_name'); // RW name  
            $table->string('community_number'); // RW number
            $table->string('district');
            $table->string('regency');
            $table->string('province');
            $table->decimal('area_size', 10, 2)->nullable()->comment('Area in hectares');
            $table->integer('population')->default(0);
            $table->string('postal_code', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['neighborhood_number', 'community_number']);
            $table->index(['type', 'is_active']);
            $table->index(['district', 'regency']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};