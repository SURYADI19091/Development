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
        Schema::table('letter_templates', function (Blueprint $table) {
            // Template files - support for Word documents
            $table->string('template_file')->nullable()->after('description')->comment('Path ke file Word template (.docx)');
            $table->string('template_file_original_name')->nullable()->after('template_file')->comment('Nama file asli yang diupload');
            $table->bigInteger('template_file_size')->nullable()->after('template_file_original_name')->comment('Ukuran file dalam bytes');
            $table->string('template_file_mime_type')->nullable()->after('template_file_size')->comment('MIME type file template');
            
            // Template type and configuration
            $table->enum('template_type', ['word', 'html'])->default('html')->after('template_file_mime_type')->comment('Jenis template: word atau html');
            $table->json('replacement_map')->nullable()->after('variables')->comment('Mapping placeholder ke field data');
            $table->json('word_bookmarks')->nullable()->after('replacement_map')->comment('Bookmark yang ada di Word template');
            
            // Usage tracking
            $table->integer('usage_count')->default(0)->after('sort_order')->comment('Jumlah penggunaan template');
            $table->timestamp('last_used_at')->nullable()->after('usage_count')->comment('Terakhir kali template digunakan');
            
            // Word processing settings
            $table->json('word_settings')->nullable()->after('margins')->comment('Pengaturan khusus Word processing');
            
            // Indexes for performance
            $table->index('template_type');
            $table->index('usage_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_templates', function (Blueprint $table) {
            // Check and drop indexes safely
            if (Schema::hasIndex('letter_templates', 'letter_templates_template_type_index')) {
                $table->dropIndex('letter_templates_template_type_index');
            }
            
            if (Schema::hasIndex('letter_templates', 'letter_templates_usage_count_index')) {
                $table->dropIndex('letter_templates_usage_count_index');
            }
            
            // Drop columns if they exist
            $columnsToCheck = [
                'template_file',
                'template_file_original_name', 
                'template_file_size',
                'template_file_mime_type',
                'template_type',
                'replacement_map',
                'word_bookmarks',
                'usage_count',
                'last_used_at',
                'word_settings'
            ];
            
            $existingColumns = [];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('letter_templates', $column)) {
                    $existingColumns[] = $column;
                }
            }
            
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
