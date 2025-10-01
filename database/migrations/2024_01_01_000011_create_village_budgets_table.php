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
        Schema::create('village_budgets', function (Blueprint $table) {
            $table->id();
            $table->year('fiscal_year');
            $table->enum('budget_type', ['pendapatan', 'belanja']);
            $table->string('category');
            $table->string('sub_category')->nullable();
            $table->string('description');
            $table->decimal('planned_amount', 15, 2)->default(0);
            $table->decimal('realized_amount', 15, 2)->default(0);
            $table->decimal('percentage', 5, 2)->virtualAs('
                CASE 
                    WHEN planned_amount > 0 THEN (realized_amount / planned_amount * 100)
                    ELSE 0 
                END
            ');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['fiscal_year', 'budget_type']);
            $table->index(['category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_budgets');
    }
};