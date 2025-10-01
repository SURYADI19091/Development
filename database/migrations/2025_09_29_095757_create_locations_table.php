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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['office', 'school', 'health', 'religious', 'commercial', 'public', 'tourism', 'other']);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->json('operating_hours')->nullable();
            $table->string('icon')->default('fas fa-map-marker-alt');
            $table->string('color')->default('blue');
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_map')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('image_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['type', 'is_active']);
            $table->index(['latitude', 'longitude']);
            $table->index(['show_on_map', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
