<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['id' => 1], [
            'site_name' => 'KateringKu',
            'site_title' => 'KateringKu — Marketplace Katering Kantor',
            'meta_description' => 'Marketplace katering kantor terpercaya — nasi box, menu sehat, dan langganan mingguan dari merchant terverifikasi.',
            'meta_keywords' => 'katering kantor, nasi box, catering harian, langganan makan siang',
            'contact_email' => 'halo@kateringku.id',
            'contact_phone' => '0812-3456-7890',
            'address' => 'Jl. Jenderal Sudirman No. 45, Jakarta Selatan',
            'footer_text' => 'Marketplace katering untuk kebutuhan makan kantor — harian, mingguan, hingga event besar.',
        ]);
    }
}
