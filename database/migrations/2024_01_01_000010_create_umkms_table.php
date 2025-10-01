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
        Schema::create('umkms', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('owner_name');
            $table->enum('category', ['makanan', 'kerajinan', 'pertanian', 'jasa', 'tekstil', 'lainnya']);
            $table->text('description')->nullable();
            $table->text('address');
            $table->foreignId('settlement_id')->nullable()->constrained('settlements')->onDelete('set null');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('operating_hours')->nullable();
            $table->text('products')->nullable();
            $table->text('services')->nullable();
            $table->string('price_range', 100)->nullable();
            $table->integer('employee_count')->default(1);
            $table->decimal('monthly_revenue', 15, 2)->nullable();
            $table->string('logo_path')->nullable();
            $table->json('photos')->nullable()->comment('JSON array of image paths');
            $table->decimal('rating', 3, 2)->default(0.0);
            $table->integer('total_reviews')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->date('registered_at')->nullable();
            $table->timestamps();

            $table->index(['category', 'is_active']);
            $table->index(['settlement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkms');
    }
};