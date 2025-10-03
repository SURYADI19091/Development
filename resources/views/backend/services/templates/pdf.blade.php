<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $letterTemplate->name ?? 'Template Surat' }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 0;
            padding: {{ $letterTemplate->margin_top ?? 2.5 }}cm {{ $letterTemplate->margin_right ?? 2.5 }}cm {{ $letterTemplate->margin_bottom ?? 2.5 }}cm {{ $letterTemplate->margin_left ?? 2.5 }}cm;
            color: #000;
        }
        
        .letter-header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        
        .letter-header img {
            max-height: 100px;
            margin-bottom: 10px;
        }
        
        .letter-header h1 {
            font-size: 18pt;
            margin: 0;
            font-weight: bold;
        }
        
        .letter-header h2 {
            font-size: 16pt;
            margin: 0;
            font-weight: bold;
        }
        
        .letter-header p {
            margin: 5px 0;
            font-size: 11pt;
        }
        
        .letter-number {
            text-align: center;
            margin: 20px 0;
            text-decoration: underline;
            font-weight: bold;
            font-size: 14pt;
        }
        
        .letter-content {
            text-align: justify;
            margin: 20px 0;
        }
        
        .letter-content p {
            margin-bottom: 15px;
        }
        
        .letter-footer {
            margin-top: 40px;
        }
        
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 40px;
        }
        
        .signature {
            display: table-cell;
            text-align: center;
            width: 45%;
            vertical-align: top;
        }
        
        .signature-space {
            height: 80px;
            margin: 20px 0;
        }
        
        /* Remove any highlight styles for PDF */
        .variable-highlight {
            background: transparent;
            border: none;
            padding: 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    @if($letterTemplate->letter_header || $letterTemplate->header_logo)
    <div class="letter-header">
        @if($letterTemplate->header_logo)
            <img src="{{ public_path('storage/' . $letterTemplate->header_logo) }}" alt="Logo Desa">
        @endif
        
        @if($letterTemplate->letter_header)
            {!! processTemplateVariables($letterTemplate->letter_header) !!}
        @endif
    </div>
    @endif

    <!-- Letter Number -->
    @if($showLetterNumber ?? true)
    <div class="letter-number">
        <u>SURAT {{ strtoupper($letterTemplate->letter_type_name) }}</u><br>
        {!! processTemplateVariables('Nomor: {{letter_number}}') !!}
    </div>
    @endif

    <!-- Content -->
    <div class="letter-content">
        {!! processTemplateVariables($letterTemplate->template_content) !!}
    </div>

    <!-- Footer -->
    @if($letterTemplate->letter_footer)
    <div class="letter-footer">
        {!! processTemplateVariables($letterTemplate->letter_footer) !!}
    </div>
    @endif
</body>
</html>

@php
function processTemplateVariables($content) {
    // Sample data for PDF
    $sampleData = [
        'village_name' => 'Desa Contoh',
        'village_address' => 'Jalan Raya Desa No. 123, Kecamatan Contoh, Kabupaten Contoh',
        'village_phone' => '(021) 1234567',
        'village_email' => 'desa.contoh@email.com',
        'head_name' => 'Bapak Kepala Desa',
        'head_nip' => '123456789012345678',
        'full_name' => 'John Doe',
        'nik' => '1234567890123456',
        'birth_place' => 'Jakarta',
        'birth_date' => '01 Januari 1990',
        'gender' => 'Laki-laki',
        'religion' => 'Islam',
        'marital_status' => 'Kawin',
        'occupation' => 'Swasta',
        'address' => 'Jl. Contoh No. 123',
        'rt' => '001',
        'rw' => '002',
        'phone' => '081234567890',
        'email' => 'john.doe@email.com',
        'purpose' => 'Untuk keperluan administrasi',
        'letter_number' => '001/KEL/DS/10/2025',
        'current_date' => date('d F Y')
    ];
    
    // Replace variables with sample data
    foreach ($sampleData as $key => $value) {
        // Handle both {{variable}} and {{ variable }} formats
        $content = str_replace('{{' . $key . '}}', $value, $content);
        $content = str_replace('{{ ' . $key . ' }}', $value, $content);
        $content = str_replace('{' . $key . '}', $value, $content);
    }
    
    // Convert newlines to proper HTML
    return nl2br($content);
}
@endphp