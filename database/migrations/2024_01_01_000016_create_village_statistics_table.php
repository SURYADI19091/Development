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
        Schema::create('village_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('statistic_type', 100);
            $table->string('category', 100)->nullable();
            $table->string('label');
            $table->integer('value');
            $table->decimal('percentage', 5, 2)->nullable();
            $table->year('year');
            $table->tinyInteger('month')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['statistic_type', 'year']);
            $table->index(['category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_statistics');
    }
};