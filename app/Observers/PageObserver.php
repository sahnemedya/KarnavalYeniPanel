<?php

namespace App\Observers;

use App\Models\Page;
use App\Services\LogService;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    /**
     * Handle the Page "created" event.
     */
    public function created(Page $page): void
    {
        LogService::add("Page Observe Create", "success", $page->title . " Veritabanına Kaydedildi.");
        Cache::forget('pages');
        Cache::remember('pages', now()->addDay(), function () {
            return Page::all();
        });
    }

    /**
     * Handle the Page "updated" event.
     */
    public function updated(Page $page): void
    {
        LogService::add("Page Observe Update", "success", $page->title . " Veritabanında Güncellendi.");
        Cache::forget('pages');
        Cache::remember('pages', now()->addDay(), function () {
            return Page::all();
        });
    }

    /**
     * Handle the Page "deleted" event.
     */
    public function deleted(Page $page): void
    {
        LogService::add("Page Observe Delete", "success", $page->title . " Veritabanında Güncellendi.");
        Cache::forget('pages');
        Cache::remember('pages', now()->addDay(), function () {
            return Page::all();
        });
    }

    /**
     * Handle the Page "restored" event.
     */
    public function restored(Page $page): void
    {
        LogService::add("Page Observe Restored", "success", $page->title . " Veritabanında Güncellendi.");
        Cache::forget('pages');
        Cache::remember('pages', now()->addDay(), function () {
            return Page::all();
        });
    }

    /**
     * Handle the Page "force deleted" event.
     */
    public function forceDeleted(Page $page): void
    {
        LogService::add("Page Observe ForceDelete", "success", $page->title . " Veritabanında Güncellendi.");
        Cache::forget('pages');
        Cache::remember('pages', now()->addDay(), function () {
            return Page::all();
        });
    }
}
