<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GeoService;
use Illuminate\Support\Facades\File;

class GenerateLlmsTxt extends Command
{
    protected $signature = 'geo:generate-llms-txt';
    protected $description = 'Generate LLMS.txt file for AI platforms';

    public function handle()
    {
        try {
            $geoService = app(GeoService::class);
            $content = $geoService->generateLlmsTxt();

            File::put(public_path('llms.txt'), $content);

            // Console context varsa output yaz
            if ($this->output) {
                $this->info('✅ LLMS.txt file generated successfully!');
                $this->info('📁 File: ' . public_path('llms.txt'));
            }

            return 0;

        } catch (\Exception $e) {
            // Hata varsa log'a yaz
            \Log::error('LLMS.txt generation error: ' . $e->getMessage());

            if ($this->output) {
                $this->error('❌ Error: ' . $e->getMessage());
            }

            return 1;
        }
    }
}
