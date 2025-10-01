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
            $table->string('position', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('village_officials', function (Blueprint $table) {
            $table->enum('position', [
                'kepala_desa', 
                'sekretaris_desa', 
                'kaur_pemerintahan', 
                'kaur_keuangan', 
                'kaur_pelayanan', 
                'kadus', 
                'staff'
            ])->change();
        });
    }
};
