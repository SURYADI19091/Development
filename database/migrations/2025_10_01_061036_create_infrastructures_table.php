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
        Schema::create('infrastructures', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // education, health, worship, economy, transportation, utilities
            $table->string('name');     // sd, mi, paud_tk, pesantren, etc.
            $table->string('label');    // "Sekolah Dasar (SD)", "Madrasah Ibtidaiyah (MI)"
            $table->decimal('value', 8, 2); // 2, 1, 3, 1, etc.
            $table->string('unit')->nullable(); // "unit", "km", "%"
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infrastructures');
    }
};
