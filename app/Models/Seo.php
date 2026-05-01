<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seo extends Model
{
    use HasFactory;

    protected $fillable = ["title","description","geo_title", "geo_description","canonical","page_id"];
    // Page ilişkisi
    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    // GEO aktif mi kontrolü
    public function scopeGeoActive($query)
    {
        return $query->whereNotNull('geo_title')
            ->whereNotNull('geo_description')
            ->where('geo_title', '!=', '')
            ->where('geo_description', '!=', '');
    }
    /**
     * Model events - LLMS.txt otomatik güncelleme
     */
    protected static function boot()
    {
        parent::boot();

        // SEO kaydedildiğinde
        static::saved(function ($seo) {
            // Debug için basit log
            error_log("SEO SAVED EVENT ÇALIŞTI - ID: " . $seo->id);

            // Eğer GEO alanları değiştiyse güncelle
            if (!empty($seo->geo_title) || !empty($seo->geo_description)) {
                error_log("GEO alanları dolu, LLMS güncelleniyor...");
                try {
                    \Illuminate\Support\Facades\Artisan::call('geo:generate-llms-txt');
                    error_log('LLMS.txt güncellendi - SEO saved: ' . $seo->page_id);
                } catch (\Exception $e) {
                    error_log('LLMS.txt güncelleme hatası: ' . $e->getMessage());
                }
            } else {
                error_log("GEO alanları boş veya eksik");
            }
        });
    }

}
