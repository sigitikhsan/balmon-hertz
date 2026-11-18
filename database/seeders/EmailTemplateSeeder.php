<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'key' => EmailTemplate::ACCEPTED,
                'subject' => 'Pembaruan lamaran :application_no - :status',
                'body' => "Halo :nama,\n\nLamaran :application_no untuk posisi :job_title dinyatakan :status.\n\nTim HRD Balmon Hertz",
            ],
            [
                'key' => EmailTemplate::REJECTED,
                'subject' => 'Pembaruan lamaran :application_no - :status',
                'body' => "Halo :nama,\n\nLamaran :application_no untuk posisi :job_title dinyatakan :status.\nAlasan: :note\n\nTim HRD Balmon Hertz",
            ],
            [
                'key' => EmailTemplate::NEED_REVISION,
                'subject' => 'Tindakan diperlukan untuk lamaran :application_no',
                'body' => "Halo :nama,\n\nLamaran :application_no untuk posisi :job_title memerlukan perbaikan.\nCatatan: :note\n\nSilakan ikuti instruksi dari tim HRD Balmon Hertz untuk mengirimkan perbaikan dokumen.",
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::firstOrCreate(
                ['key' => $template['key']],
                array_merge($template, ['is_active' => true])
            );
        }
    }
}
