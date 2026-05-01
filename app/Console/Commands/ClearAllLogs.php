<?php

namespace App\Console\Commands;

use App\Services\LogService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ClearAllLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cdr.log ve laravel.log dosyalarını temizler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            app(LogService::class)->clearCustomLogs();

            $logPath = storage_path('logs/laravel.log');
            if (file_exists($logPath)) {
                file_put_contents($logPath, '');
            }
            Log::info('Tüm loglar başarıyla temizlendi (artisan logs:clear-all).');
            $this->info('Tüm loglar temizlendi.');
        } catch (\Exception $exception) {
            Log::info('Loglar temizlenemedi (artisan logs:clear-all). Hata => ' . $exception->getMessage());
            $this->info('Tüm loglar temizlendi.');
        }
        // Özel log temizleme


        // Laravel log temizliği


    }
}
