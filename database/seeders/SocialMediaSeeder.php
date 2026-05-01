<?php

namespace Database\Seeders;

use App\Models\SocialMedia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialMedia::updateOrCreate([
            'id' => 1
        ], [
            'contacts_id' => 1,
            'facebook' => 'https://www.facebook.com/',
            'instagram' => 'https://www.instagram.com/',
            'twitter' => 'https://x.com/',
            'linkedin' => 'https://www.linkedin.com/company/',
            'youtube' => 'https://www.youtube.com/',
            'tiktok' => 'https://www.tiktok.com/',
            'whatsapp' => '+905442621505',
            'telegram' => null,
            'behance' => null,
            'pinterest' => null,
            'threads' => null,
            'reddit' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}