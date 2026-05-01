<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\LogService;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        LogService::add("Category Observe Created", "success", $category->name . " Veritabanına Kaydedildi.");
        Cache::forget('categories');
        Cache::remember('categories', now()->addDay(), function () {
            return Category::all();
        });

    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        LogService::add("Category Observe Updated", "success", $category->name . " Veritabanında Güncellendi.");
        Cache::forget('categories');
        Cache::remember('categories', now()->addDay(), function () {
            return Category::all();
        });
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        LogService::add("Category Observe Deleted", "success", $category->name . " Veritabanından Silindi. (Soft Delete)");
        Cache::forget('categories');
        Cache::remember('categories', now()->addDay(), function () {
            return Category::all();
        });
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        LogService::add("Category Observe Restored", "success", $category->name . " Geri Yüklendi .");
        Cache::forget('categories');
        Cache::remember('categories', now()->addDay(), function () {
            return Category::all();
        });
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        LogService::add("Category Observe Deleted", "success", $category->name . " Veritabanından Silindi. (Force Delete)");
        Cache::forget('categories');
        Cache::remember('categories', now()->addDay(), function () {
            return Category::all();
        });
    }
}
