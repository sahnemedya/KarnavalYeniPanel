<?php

namespace App\Observers;

use App\Models\SiteSettings;
use Illuminate\Support\Facades\Cache;

class SiteSettingsObserver
{
    /**
     * Handle the SiteSettings "created" event.
     */
    public function created(SiteSettings $siteSettings): void
    {
        Cache::forget('siteSettings');
        $siteSettings = Cache::remember('siteSettings', now()->addDay(), function () {
            return SiteSettings::first();
        });
    }

    /**
     * Handle the SiteSettings "updated" event.
     */
    public function updated(SiteSettings $siteSettings): void
    {
        Cache::forget('siteSettings');
        $siteSettings = Cache::remember('siteSettings', now()->addDay(), function () {
            return SiteSettings::first();
        });
    }
}
