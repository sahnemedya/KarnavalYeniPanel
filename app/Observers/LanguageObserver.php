<?php

namespace App\Observers;

use App\Models\Language;
use App\Services\LogService;
use Illuminate\Support\Facades\Cache;

class LanguageObserver
{
    /**
     * Handle the Language "created" event.
     */
    public function created(Language $language): void
    {
        LogService::add("Language Observe Created", "success", $language->name . " Veritabanına Kaydedildi.");
        Cache::forget('languages');
        Cache::remember('languages', now()->addDay(), function () {
            return Language::all();
        });
    }

    /**
     * Handle the Language "deleted" event.
     */
    public function deleted(Language $language): void
    {
        LogService::add("Language Observe Deleted", "success", $language->name . " Kaydı Silindi.");
        Cache::forget('languages');
        Cache::remember('languages', now()->addDay(), function () {
            return Language::all();
        });

    }


}
