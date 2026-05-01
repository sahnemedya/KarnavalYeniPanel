<?php

namespace App\Observers;

use App\Models\Seo;
use App\Services\LogService;
use Illuminate\Support\Facades\Cache;

class SeoObserve
{
    /**
     * Handle the Seo "created" event.
     */
    public function created(Seo $seo): void
    {
        LogService::add("Seo Observe Created", "success", $seo->title . " Veritabanına Kaydedildi.");
        Cache::forget('seo');
        Cache::remember('seo', now()->addDay(), function () {
            return Seo::all();
        });
    }

    /**
     * Handle the Seo "updated" event.
     */
    public function updated(Seo $seo): void
    {
        LogService::add("Seo Observe Updated", "success", $seo->title . " Veritabanında Güncellendi.");
        Cache::forget('seo');
        Cache::remember('seo', now()->addDay(), function () {
            return Seo::all();
        });
    }

    /**
     * Handle the Seo "deleted" event.
     */
    public function deleted(Seo $seo): void
    {
        LogService::add("Seo Observe Deleted", "success", $seo->title . " Veritabanından Silindi.");
        Cache::forget('seo');
        Cache::remember('seo', now()->addDay(), function () {
            return Seo::all();
        });
    }
}
