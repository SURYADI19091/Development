<?php

namespace Database\Seeders;

use App\Models\LetterTemplate;
            'letter_header' => 'PEMERINTAH KABUPATEN MAJALENGKA
KECAMATAN ARGAPURA
DESA {{village_name}}
{{village_address}}
Telp. {{village_phone}}',
            'letter_footer' => '{{village_name}}, {{current_date}}

Kepala Desa {{village_name}}



{{head_name}}
NIP. {{head_nip}}',e\Database\Seeder;

class LetterTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Template Surat Keterangan Domisili
        LetterTemplate::create([
            'name' => 'Template Surat Keterangan Domisili',
            'code' => 'DOMISILI_001',
            'letter_type' => 'domisili',
            'description' => 'Template standar untuk surat keterangan domisili warga',
            'template_content' => '
                <p>Yang bertanda tangan di bawah ini, Kepala Desa {{village_name}}, dengan ini menerangkan bahwa:</p>
                
                <table style="margin: 20px 0; width: 100%;">
                    <tr>
                        <td width="30%">Nama Lengkap</td>
                        <td width="5%">:</td>
                        <td>{{full_name}}</td>
                    </tr>
                    <tr>
                        <td>NIK</td>
                        <td>:</td>
                        <td>{{nik}}</td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanggal Lahir</td>
                        <td>:</td>
                        <td>{{birth_place}}, {{birth_date}}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td>{{gender}}</td>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td>:</td>
                        <td>{{religion}}</td>
                    </tr>
                    <tr>
                        <td>Pekerjaan</td>
                        <td>:</td>
                        <td>{{occupation}}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>{{address}}, RT {{rt}}/RW {{rw}}</td>
                    </tr>
                </table>
                
                <p>Adalah benar-benar warga yang berdomisili di wilayah {{village_name}} dan tercatat dalam Kartu Keluarga dengan data yang sah.</p>
                
                <p>Surat keterangan ini dibuat untuk keperluan: <strong>{{purpose}}</strong></p>
                
                <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
            ',
            'letter_header' => 'PEMERINTAH KABUPATEN MAJALENGKA
KECAMATAN ARGAPURA
DESA {{'{{village_name}}'}}
{{'{{village_address}}'}}
Telp. {{'{{village_phone}}'}}',
            'letter_footer' => '{{'{{village_name}}'}}, {{'{{current_date}}'}}

Kepala Desa {{'{{village_name}}'}}



{{'{{head_name}}'}}
NIP. {{'{{head_nip}}'}}',
            'required_fields' => ['full_name', 'nik', 'birth_place', 'birth_date', 'gender', 'religion', 'occupation', 'address', 'rt', 'rw', 'purpose'],
            'variables' => ['village_name', 'village_address', 'village_phone', 'head_name', 'head_nip', 'current_date', 'letter_number'],
            'format' => 'A4',
            'orientation' => 'portrait',
            'margin_top' => 2.5,
            'margin_bottom' => 2.5,
            'margin_left' => 2.5,
            'margin_right' => 2.5,
            'is_active' => true,
            'sort_order' => 1,
            'created_by' => 1,
        ]);

        // Template Surat Keterangan Usaha
        LetterTemplate::create([
            'name' => 'Template Surat Keterangan Usaha',
            'code' => 'USAHA_001',
            'letter_type' => 'usaha',
            'description' => 'Template standar untuk surat keterangan usaha',
            'template_content' => '
                <p>Yang bertanda tangan di bawah ini, Kepala Desa {{'{{village_name}}'}}, dengan ini menerangkan bahwa:</p>
                
                <table style="margin: 20px 0; width: 100%;">
                    <tr>
                        <td width="30%">Nama Lengkap</td>
                        <td width="5%">:</td>
                        <td>{{'{{full_name}}'}}</td>
                    </tr>
                    <tr>
                        <td>NIK</td>
                        <td>:</td>
                        <td>{{'{{nik}}'}}</td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanggal Lahir</td>
                        <td>:</td>
                        <td>{{'{{birth_place}}'}}, {{'{{birth_date}}'}}</td>
                    </tr>
                    <tr>
                        <td>Pekerjaan</td>
                        <td>:</td>
                        <td>{{'{{occupation}}'}}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>{{'{{address}}'}}, RT {{'{{rt}}'}}/RW {{'{{rw}}'}}</td>
                    </tr>
                </table>
                
                <p>Adalah benar warga kami yang mempunyai usaha dan benar-benar menjalankan usaha dengan baik serta tidak pernah melanggar ketentuan yang berlaku.</p>
                
                <p>Surat keterangan ini dibuat untuk keperluan: <strong>{{'{{purpose}}'}}</strong></p>
                
                <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
            ',
            'letter_header' => 'PEMERINTAH KABUPATEN MAJALENGKA
KECAMATAN ARGAPURA
DESA {{'{{village_name}}'}}
{{'{{village_address}}'}}
Telp. {{'{{village_phone}}'}}',
            'letter_footer' => '{{'{{village_name}}'}}, {{'{{current_date}}'}}

Kepala Desa {{'{{village_name}}'}}



{{'{{head_name}}'}}
NIP. {{'{{head_nip}}'}}',
            'required_fields' => ['full_name', 'nik', 'birth_place', 'birth_date', 'occupation', 'address', 'rt', 'rw', 'purpose'],
            'variables' => ['village_name', 'village_address', 'village_phone', 'head_name', 'head_nip', 'current_date', 'letter_number'],
            'format' => 'A4',
            'orientation' => 'portrait',
            'margin_top' => 2.5,
            'margin_bottom' => 2.5,
            'margin_left' => 2.5,
            'margin_right' => 2.5,
            'is_active' => true,
            'sort_order' => 2,
            'created_by' => 1,
        ]);

        // Template Surat Keterangan Tidak Mampu
        LetterTemplate::create([
            'name' => 'Template Surat Keterangan Tidak Mampu',
            'code' => 'TIDAK_MAMPU_001',
            'letter_type' => 'tidak_mampu',
            'description' => 'Template standar untuk surat keterangan tidak mampu',
            'template_content' => '
                <p>Yang bertanda tangan di bawah ini, Kepala Desa {{'{{village_name}}'}}, dengan ini menerangkan bahwa:</p>
                
                <table style="margin: 20px 0; width: 100%;">
                    <tr>
                        <td width="30%">Nama Lengkap</td>
                        <td width="5%">:</td>
                        <td>{{'{{full_name}}'}}</td>
                    </tr>
                    <tr>
                        <td>NIK</td>
                        <td>:</td>
                        <td>{{'{{nik}}'}}</td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanggal Lahir</td>
                        <td>:</td>
                        <td>{{'{{birth_place}}'}}, {{'{{birth_date}}'}}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td>{{'{{gender}}'}}</td>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td>:</td>
                        <td>{{'{{religion}}'}}</td>
                    </tr>
                    <tr>
                        <td>Pekerjaan</td>
                        <td>:</td>
                        <td>{{'{{occupation}}'}}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>{{'{{address}}'}}, RT {{'{{rt}}'}}/RW {{'{{rw}}'}}</td>
                    </tr>
                </table>
                
                <p>Adalah benar-benar warga yang berdomisili di wilayah {{'{{village_name}}'}} dan berdasarkan pengamatan serta keterangan yang dapat dipercaya, yang bersangkutan adalah termasuk keluarga <strong>TIDAK MAMPU/MISKIN</strong>.</p>
                
                <p>Surat keterangan ini dibuat untuk keperluan: <strong>{{'{{purpose}}'}}</strong></p>
                
                <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
            ',
            'letter_header' => 'PEMERINTAH KABUPATEN MAJALENGKA
KECAMATAN ARGAPURA
DESA {{'{{village_name}}'}}
{{'{{village_address}}'}}
Telp. {{'{{village_phone}}'}}',
            'letter_footer' => '{{'{{village_name}}'}}, {{'{{current_date}}'}}

Kepala Desa {{'{{village_name}}'}}



{{'{{head_name}}'}}
NIP. {{'{{head_nip}}'}}',
            'required_fields' => ['full_name', 'nik', 'birth_place', 'birth_date', 'gender', 'religion', 'occupation', 'address', 'rt', 'rw', 'purpose'],
            'variables' => ['village_name', 'village_address', 'village_phone', 'head_name', 'head_nip', 'current_date', 'letter_number'],
            'format' => 'A4',
            'orientation' => 'portrait',
            'margin_top' => 2.5,
            'margin_bottom' => 2.5,
            'margin_left' => 2.5,
            'margin_right' => 2.5,
            'is_active' => true,
            'sort_order' => 3,
            'created_by' => 1,
        ]);
    }
}
