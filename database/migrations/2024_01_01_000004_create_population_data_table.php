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
        Schema::create('population_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('serial_number');
            $table->char('family_card_number', 16);
            $table->char('identity_card_number', 16)->unique();
            $table->string('name');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('age');
            $table->string('address');
            $table->string('settlement_id');
            $table->enum('gender', ['M', 'F']);
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed']);
            $table->string('family_relationship')->comment('Family Relationship Status');
            $table->string('head_of_family')->comment('Head of Family');
            $table->string('religion');
            $table->string('occupation');
            $table->string('residence_type')->comment('Type of Residence');
            $table->string('independent_family_head')->comment('Independent Family Head');
            $table->string('district');
            $table->string('regency');
            $table->string('province');
            $table->timestamps();

            $table->index(['family_card_number']);
            $table->index(['district', 'regency']);
            $table->index(['gender']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('population_data');
    }
};