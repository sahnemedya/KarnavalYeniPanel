<?php

namespace Database\Seeders;

use App\Models\ApiKeys;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        ApiKeys::updateOrCreate([
            'id' => 1,
            'youtube_channel_id' => NULL,
            'recaptcha_site_key' => NULL,
            'recaptcha_secret_key' => NULL,
        ]);
    }
}
