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
        Schema::table('population_data', function (Blueprint $table) {
            $table->enum('status', ['Hidup', 'Meninggal'])->default('Hidup')->after('province');
            $table->date('death_date')->nullable()->after('status')->comment('Tanggal meninggal jika status = Meninggal');
            $table->string('death_cause')->nullable()->after('death_date')->comment('Penyebab kematian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('population_data', function (Blueprint $table) {
            $table->dropColumn(['status', 'death_date', 'death_cause']);
        });
    }
};
