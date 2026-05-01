<?php

namespace App\Services;

use App\Http\Requests\LanguageStoreRequest;
use Illuminate\Support\Facades\Log;

class LogService
{
    public static function add($process, $status, $message)
    {
        Log::channel("cmsLog")->info("[ " . $status . " ] " . $process . " => " . $message);
    }

    public function clearCustomLogs(): void
    {
        $customLogFiles = [
            storage_path('logs/cdr.log'),
            // başka log dosyaları eklenebilir
        ];
        try {
            foreach ($customLogFiles as $file) {
                if (file_exists($file)) {
                    file_put_contents($file, ''); // Dosya içeriğini sıfırla
                }
            }
            LogService::add("LogService ClearCustomLogs", "success","Loglar Temizlendi.");
        } catch (\Exception $exception) {
            LogService::add("LogService ClearCustomLogs ","error","Log Temizleme Hatası => ". $exception->getMessage());
        }

    }
}
