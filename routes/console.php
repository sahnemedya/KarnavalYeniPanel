<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
// GEO Command'ı ekle
Artisan::command('geo:generate-llms-txt', function () {
    $command = new \App\Console\Commands\GenerateLlmsTxt();
    return $command->handle();
})->purpose('Generate LLMS.txt file for AI platforms');
