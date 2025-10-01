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
        Schema::create('budget_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained('village_budgets')->onDelete('cascade');
            $table->date('transaction_date');
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->enum('transaction_type', ['income', 'expense']);
            $table->string('reference_number', 100)->nullable();
            $table->string('attachment_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['budget_id', 'transaction_date']);
            $table->index(['transaction_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_transactions');
    }
};