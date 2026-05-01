<?php

namespace App\Observers;

use App\Models\Blade;
use App\Services\LogService;
use Illuminate\Support\Facades\Cache;

class BladeObserver
{

    /**
     * Handle the Blade "created" event.
     */
    public function created(Blade $blade): void
    {
        LogService::add("Blade Observe Created", "success", $blade->name . " Veritabanına Kaydedildi.");
        Cache::forget('blades');
        Cache::remember('blades', now()->addDay(), function () {
            return Blade::all();
        });
    }

    /**
     * Handle the Blade "deleted" event.
     */
    public function deleted(Blade $blade): void
    {
        LogService::add("Blade Deleted Observe", "success", $blade->name . " Kaydı Silindi.");
        Cache::forget('blades');
        Cache::remember('blades', now()->addDay(), function () {
            return Blade::all();
        });
    }





}
