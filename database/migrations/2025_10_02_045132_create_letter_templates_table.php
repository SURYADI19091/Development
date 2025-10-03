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
        Schema::create('letter_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('letter_type', [
                'domisili', 
                'usaha', 
                'tidak_mampu', 
                'penghasilan', 
                'pengantar_ktp', 
                'pengantar_kk', 
                'pengantar_akta', 
                'pengantar_nikah',
                'kelahiran',
                'kematian',
                'pindah',
                'beda_nama',
                'kehilangan',
                'lainnya'
            ]);
            $table->text('description')->nullable();
            $table->longText('template_content');
            $table->json('required_fields')->nullable()->comment('JSON array of required fields');
            $table->json('variables')->nullable()->comment('JSON array of available template variables');
            $table->string('header_logo_path')->nullable();
            $table->text('letter_header')->nullable();
            $table->text('letter_footer')->nullable();
            $table->string('format', 20)->default('A4')->comment('Paper format: A4, Legal, etc');
            $table->string('orientation', 10)->default('portrait')->comment('portrait or landscape');
            $table->json('margins')->nullable()->comment('JSON for page margins');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['letter_type', 'is_active']);
            $table->index(['code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_templates');
    }
};
