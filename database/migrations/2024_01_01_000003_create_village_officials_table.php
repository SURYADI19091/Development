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
        Schema::create('village_officials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('position', [
                'kepala_desa', 
                'sekretaris_desa', 
                'kaur_pemerintahan', 
                'kaur_keuangan', 
                'kaur_pelayanan', 
                'kadus', 
                'staff'
            ]);
            $table->string('nip', 50)->nullable();
            $table->string('education', 100)->nullable();
            $table->string('work_period', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('specialization')->nullable();
            $table->string('work_area')->nullable()->comment('untuk kadus');
            $table->boolean('is_active')->default(true);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_officials');
    }
};