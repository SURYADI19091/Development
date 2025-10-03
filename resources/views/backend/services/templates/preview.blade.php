<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $letterTemplate->name ?? 'Template Surat' }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }
        
        .page {
            background: white;
            margin: 20px auto;
            padding: {{ $letterTemplate->margin_top ?? 2.5 }}cm {{ $letterTemplate->margin_right ?? 2.5 }}cm {{ $letterTemplate->margin_bottom ?? 2.5 }}cm {{ $letterTemplate->margin_left ?? 2.5 }}cm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            min-height: calc(29.7cm - {{ ($letterTemplate->margin_top ?? 2.5) + ($letterTemplate->margin_bottom ?? 2.5) }}cm);
            width: 21cm;
            position: relative;
        }
        
        @media (max-width: 768px) {
            .page {
                width: 95%;
                margin: 10px auto;
                padding: 1cm;
            }
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
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .signature {
            text-align: center;
            width: 45%;
        }
        
        .signature-space {
            height: 80px;
            margin: 20px 0;
        }
        
        .toolbar {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(0,0,0,0.8);
            padding: 10px;
            border-radius: 5px;
        }
        
        .toolbar button {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            margin: 0 5px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .toolbar button:hover {
            background: #0056b3;
        }
        
        .variable-highlight {
            background: #fff3cd;
            border: 1px dashed #856404;
            padding: 2px 4px;
            border-radius: 3px;
        }
        
        @media print {
            body {
                background: white;
            }
            
            .page {
                margin: 0;
                box-shadow: none;
                width: 100%;
                height: 100vh;
            }
            
            .toolbar {
                display: none;
            }
            
            .variable-highlight {
                background: transparent;
                border: none;
            }
        }
    </style>
</head>
<body>
    <!-- Toolbar -->
    <div class="toolbar">
        <button onclick="window.print()">
            <i class="fas fa-print"></i> Cetak
        </button>
        <button onclick="downloadPDF()">
            <i class="fas fa-download"></i> Download PDF
        </button>
        <button onclick="window.close()">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <div class="page">
        <!-- Header -->
        @if($letterTemplate->letter_header || $letterTemplate->header_logo)
        <div class="letter-header">
            @if($letterTemplate->header_logo)
                <img src="{{ asset('storage/' . $letterTemplate->header_logo) }}" alt="Logo Desa">
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
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        function downloadPDF() {
            // Create a form to send POST request for PDF generation
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("backend.letter-templates.download-pdf", $letterTemplate->id) }}';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            // Add download parameter
            const downloadInput = document.createElement('input');
            downloadInput.type = 'hidden';
            downloadInput.name = 'download';
            downloadInput.value = 'true';
            form.appendChild(downloadInput);
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        // Auto focus for better user experience
        window.onload = function() {
            window.focus();
        };
    </script>
</body>
</html>

@php
function processTemplateVariables($content) {
    // Sample data for preview
    $sampleData = [
        'village_name' => 'Desa Contoh',
        'village_address' => 'Jalan Raya Desa No. 123, Kecamatan Contoh, Kabupaten Contoh',
        'village_phone' => '(021) 1234567',
        'village_email' => 'desa.contoh@email.com',
        'head_name' => 'Bapak Kepala Desa',
        'head_nip' => '123456789012345678',
        'full_name' => '[Nama Lengkap]',
        'nik' => '[Nomor NIK]',
        'birth_place' => '[Tempat Lahir]',
        'birth_date' => '[Tanggal Lahir]',
        'gender' => '[Jenis Kelamin]',
        'religion' => '[Agama]',
        'marital_status' => '[Status Perkawinan]',
        'occupation' => '[Pekerjaan]',
        'address' => '[Alamat Lengkap]',
        'rt' => '[RT]',
        'rw' => '[RW]',
        'phone' => '[Nomor Telepon]',
        'email' => '[Email]',
        'purpose' => '[Tujuan Penggunaan Surat]',
        'letter_number' => 'XXX/XXX/XXXX/XXXX',
        'current_date' => date('d F Y')
    ];
    
    // Replace variables with sample data
    foreach ($sampleData as $key => $value) {
        // Handle both {{variable}} and {{ variable }} formats
        $content = str_replace('{{' . $key . '}}', '<span class="variable-highlight">' . $value . '</span>', $content);
        $content = str_replace('{{ ' . $key . ' }}', '<span class="variable-highlight">' . $value . '</span>', $content);
        $content = str_replace('{' . $key . '}', '<span class="variable-highlight">' . $value . '</span>', $content);
    }
    
    // Convert newlines to proper HTML
    return nl2br($content);
}
@endphp