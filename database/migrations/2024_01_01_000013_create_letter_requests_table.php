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
        Schema::create('letter_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number', 50)->unique();
            $table->enum('letter_type', [
                'domisili', 
                'usaha', 
                'tidak_mampu', 
                'penghasilan', 
                'pengantar_ktp', 
                'pengantar_kk', 
                'pengantar_akta', 
                'pengantar_nikah', 
                'lainnya'
            ]);
            $table->string('custom_letter_type')->nullable();
            
            // Applicant data
            $table->string('full_name');
            $table->char('nik', 16);
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('gender', ['L', 'P']);
            $table->enum('religion', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);
            $table->enum('marital_status', ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']);
            $table->string('occupation');
            $table->text('address');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            
            // Letter details
            $table->text('purpose');
            
            // Files
            $table->string('ktp_file_path')->nullable();
            $table->string('kk_file_path')->nullable();
            $table->json('other_files')->nullable()->comment('JSON array of file paths');
            
            // Status
            $table->enum('status', ['pending', 'processing', 'ready', 'completed', 'rejected'])->default('pending');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->date('completion_date')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();

            $table->index(['letter_type', 'status']);
            $table->index(['nik']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_requests');
    }
};