<?php

namespace Database\Seeders;

use App\Models\SiteSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSettings::updateOrCreate([
            'id' => 1,
            'site_name' => "Site Adı",
            'logo' => "nophoto.png",
            'favicon' => "nophoto.png",
            'footer_logo' => "nophoto.png",
            'head_code' => NULL,
            'header_code' => NULL,
            'footer_code' => NULL,
            'dil_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
