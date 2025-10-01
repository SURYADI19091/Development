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
        Schema::create('agenda_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id')->constrained('agendas')->onDelete('cascade');
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['registered', 'confirmed', 'attended', 'absent'])->default('registered');
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();

            $table->unique(['agenda_id', 'phone']);
            $table->index(['agenda_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_participants');
    }
};