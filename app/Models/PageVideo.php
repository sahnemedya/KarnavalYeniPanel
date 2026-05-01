<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class PageVideo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'page_id',
        'title',
        'source_type',
        'source_value',
        'cover_image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Bu video hangi sayfaya ait?
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    /**
     * Kapak görselinin tam URL'sini getirir.
     * Page modelindeki image() metodu ile aynı pattern.
     * @return false|string
     */
    public function coverImage()
    {
        if ($this->cover_image) {
            return asset("storage/" . config('constants.video_path') . "/" . $this->cover_image);
        }
        return false;
    }

    /**
     * Eğer source_type=local ise video dosyasının URL'sini getirir.
     * @return false|string
     */
    public function videoFile()
    {
        if ($this->source_type === 'local' && $this->source_value) {
            return asset("storage/" . config('constants.video_path') . "/" . $this->source_value);
        }
        return false;
    }

    /**
     * Kullanıcının tıkladığında gideceği link.
     * - youtube: https://www.youtube.com/watch?v=XXX
     * - local:   /storage/.../video.mp4
     * @return string
     */
    public function watchUrl(): string
    {
        if ($this->source_type === 'youtube') {
            return "https://www.youtube.com/watch?v={$this->source_value}";
        }

        if ($this->source_type === 'local') {
            return $this->videoFile() ?: '#';
        }

        return '#';
    }

    /**
     * YouTube embed URL (iframe için).
     * @return string|null
     */
    public function embedUrl(): ?string
    {
        if ($this->source_type === 'youtube') {
            return "https://www.youtube.com/embed/{$this->source_value}";
        }
        return null;
    }

    // ----- SCOPES -----

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
