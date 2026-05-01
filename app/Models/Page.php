<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['hit','sezon_id', 'title','inside_title',  'slug', 'link','link2','link3', 'heyzen', 'content','location','video','ses', 'spotify', 'image', 'icon', 'file',
        'is_main', 'blade_id', 'category_id',  'translation_of', 'parent_page',  'lang_id', 'form_active', 'published',  'show_homepage', 'show_footer','show_sponsorluk','show_menu'];

    /**
     * Sayfa resimlerinin bulunduğu klasör yolunu getirir.
     * @return string
     */
    public function getImagePath()
    {
        return asset('storage/' . Config::get('constants.page_path'));
    }

    /**
     * Sayfanın resmininin yolunu getirir. Eğer resim yoksa false değeri getirir.
     * @return false|string
     */
    public function image()
    {
        if ($this->image) {
            return asset("storage/" . config('constants.page_path') . "/" . $this->image); // Resmin tam URL'sini döndürüyor
        }
        return false;
    }
    public function icon()
    {
        if ($this->icon) {
            return asset("storage/" . config('constants.page_path') . "/" . $this->icon); // Resmin tam URL'sini döndürüyor
        }
        return false;
    }

   public function file()
    {
        if ($this->file) {
            return asset("storage/" . config('constants.file_path') . "/" . $this->file); // Resmin tam URL'sini döndürüyor
        }
        return false;
    }
   public function link()
    {
        if ($this->link) {
            return asset("storage/" . config('constants.file_path') . "/" . $this->link); // Resmin tam URL'sini döndürüyor
        }
        return false;
    }
   public function ses()
    {
        if ($this->ses) {
            return asset("storage/" . config('constants.voice_path') . "/" . $this->ses); // Resmin tam URL'sini döndürüyor
        }
        return false;
    }

    public function faqs()
    {
        return $this->hasMany(FAQ::class);
    }

    public function pageVideos()
    {
        return $this->hasMany(PageVideo::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function pageSchema()
    {
        return $this->hasOne(PageSchemas::class, 'page_id');
    }

    public function effectiveGalleries()
    {
        $own = Gallery::where('page_id', $this->id)
            ->orderBy('id', 'asc')
            ->get();

        // Kendi galerisi varsa veya çeviri değilse kendi galerisini döndür
        if ($own->isNotEmpty() || !$this->translation_of) {
            return $own;
        }

        // Çeviri sayfası ve kendi galerisi yok → kaynak sayfanın galerisi
        return Gallery::where('page_id', $this->translation_of)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Tüm videolar (admin paneli için, pasifler dahil).
     */
    public function allPageVideos()
    {
        return $this->hasMany(\App\Models\PageVideo::class, 'page_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function translations()
    {
        return $this->hasMany(Page::class, 'translation_of');
    }

    /**
     * Bu sayfa bir çeviri ise, bağlı olduğu ana sayfayı getirir.
     */
    public function originalPage()
    {
        return $this->belongsTo(Page::class, 'translation_of');
    }

    /**
     * Mevcut sayfanın diğer dillerdeki versiyonlarını (kendisi dahil) bulmak için yardımcı.
     * Frontend'de dil değiştirici (Language Switcher) için kritiktir.
     */

    /**
     * Sayfanın blade dosyasına ait ilişkiyi getirir.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function blade()
    {
        return $this->belongsTo(Blade::class, 'blade_id');
    }

    /**
     * Sayfanın blade dosyasının view path'ini getirir.
     * @return false|string
     */
    public function bladePath()
    {
        if ($this->blade_id && $this->blade && $this->blade->file) {
            $fileName = str_replace('.blade.php', '', $this->blade->file);
            return 'user.blades.' . $fileName;
        }
        return false;
    }

    /**
     * Sayfanın blade dosyasının mevcut olup olmadığını kontrol eder.
     * @return bool
     */
    public function bladeExists()
    {
        $viewPath = $this->bladePath();
        return $viewPath && view()->exists($viewPath);
    }

    /**
     * Sayfanın blade dosyasının tam klasör yolunu getirir.
     * @return string
     */
    public function getBladePath()
    {
        return resource_path('views/user/blades/');
    }

    /**
     * Sayfanın galeri resimlerini getirir.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }

    /**
     * Geçerli sayfanın üst sayfasını getirir.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_page');
    }

    /**
     * Geçerli sayfanın üst kategorisini getirir.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Bu sayfanın kategorinin ana sayfası olup olmadığını kontrol eder.
     * @return bool
     */
    public function isMainPageOfCategory(): bool
    {
        return $this->parentCategory &&
            $this->parentCategory->name === $this->title;
    }

    /**
     * Geçerli sayfanın breadcrumb listesini verir eğer sayfa alt sayfa olarak kaydedilmişse ust kategorinin breadcrumb listesini ekleyerek devam eder
     * @return \Illuminate\Support\Collection
     */
    public function breadcrumbs()
    {
        $breadcrumbs = collect();

        if ($this->parent) {
            $breadcrumbs = $this->parent->breadcrumbs();
        } elseif ($this->parentCategory && !$this->isMainPageOfCategory()) {
            $breadcrumbs = $this->parentCategory->breadcrumbs();
        }

        $breadcrumbs->push($this);

        return $breadcrumbs;
    }

    // SEO ilişkisi
    public function seo()
    {
        return $this->hasOne(Seo::class, 'page_id');
    }

    // Sadece GEO aktif sayfalar
    public function scopeWithGeo($query)
    {
        return $query->whereHas('seo', function ($q) {
            $q->whereNotNull('geo_title')
                ->whereNotNull('geo_description')
                ->where('geo_title', '!=', '')
                ->where('geo_description', '!=', '');
        });
    }

    // Aktif sayfalar
//    public function scopeActive($query)
//    {
//        return $query->where('status', 'active');
//    }

    // GEO aktif mi kontrolü
    public function hasGeo(): bool
    {
        return $this->seo &&
            !empty($this->seo->geo_title) &&
            !empty($this->seo->geo_description);
    }

    /**
     * Geçerli sayfanın alt sayfalarını getirir.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Page::class, 'parent_page');
    }

    /**
     * Geçerli sayfanın tüm alt sayfalarını recursive olarak getirir.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren')->orderBy('id','asc');
    }

    public function karnavalSezonus()
    {
        return $this->belongsTo(KarnavalSezonu::class, 'sezon_id');
    }

    public function references(): HasManyThrough
    {
        return $this->hasManyThrough(
            References::class, // ulaşmak istediğin model
            KarnavalSezonu::class,      // ara model
            'id',              // sezon tablosundaki PK (Sezon.id)
            'sezon_id',        // references tablosundaki FK
            'sezon_id',        // page tablosundaki local key
            'id'               // sezon tablosundaki local key
        );
    }

    public function allChildrenBlog()
    {
        return $this->children()->with('allChildren')->where('published',1)->orderBy('id', 'desc');
    }
    /**
     * Hedef dil ID'sine göre bu sayfanın çevirisini bulur.
     * @param int $targetLangId
     * @return Page|null
     */
    public function getTranslationOf($targetLangId)
    {
        // Zaten o dildeysek kendisini döndür
        if ($this->lang_id == $targetLangId) {
            return $this;
        }

        // Bu sayfa bir çeviri ise, ana sayfanın ID'sini (translation_of) al, değilse kendi ID'sini al
        $masterId = $this->translation_of ?? $this->id;

        return self::where('lang_id', $targetLangId)
            // Sadece yayınlanmışları getir (isteğe bağlı, admin panelindeyse kaldırılabilir)
            // ->where('published', 1)
            ->where(function ($query) use ($masterId) {
                $query->where('id', $masterId)
                    ->orWhere('translation_of', $masterId);
            })
            ->first(); // <--- ÖNEMLİ: first() olduğu için Builder değil Model döner.
    }

    /**
     * Controller'daki hatayı çözmek için alias (takma ad) ekliyoruz.
     * getTranslationFor çağrıldığında getTranslationOf çalışacak.
     */
    public function getTranslationFor($targetLangId)
    {
        return $this->getTranslationOf($targetLangId);
    }

    /**
     * Sayfanın ait olduğu dili getirir.
     * (Create sayfasındaki hatayı önlemek için bu da gerekli)
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'lang_id', 'id');
    }

    /**
     * Sayfanın hierarşi derinliğini hesaplar (LLMS.txt için # sayısı).
     * @return int
     */
    public function getDepth(): int
    {
        $depth = 1;
        $current = $this;

        while ($current->parent_page !== null && $current->parent_page !== 0) {
            $depth++;
            if ($current->parent) {
                $current = $current->parent;
            } else {
                break; // Parent yoksa döngüyü kır
            }

            // Sonsuz döngü koruması
            if ($depth > 10) break;
        }

        return $depth;
    }

    /**
     * LLMS.txt için sayfa bilgilerini optimize edilmiş formatta getirir.
     * @return array
     */
    public function getLlmsData(): array
    {
        if (!$this->hasGeo()) {
            return [];
        }

        return [
            'id' => $this->id,
            'title' => $this->seo->geo_title,
            'description' => $this->seo->geo_description,
            'slug' => $this->slug,
            'parent_page' => $this->parent_page,
            'depth' => $this->getDepth(),
            'updated_at' => $this->updated_at
        ];
    }

    /**
     * Model events - LLMS.txt otomatik güncelleme
     */
    /**
     * Model events - LLMS.txt otomatik güncelleme ve Dosya Temizliği
     */
    protected static function boot()
    {
        parent::boot();

        // Sayfa kaydedildiğinde LLMS güncelleme
        static::saved(function ($page) {
            if ($page->hasGeo()) {
                defer(function () use ($page) {
                    try {
                        \Illuminate\Support\Facades\Artisan::call('geo:generate-llms-txt');
                    } catch (\Exception $e) {
                        \Log::error('LLMS.txt güncelleme hatası: ' . $e->getMessage());
                    }
                });
            }
        });

        // ==========================================================
        // YENİ: ÇÖPE ATILDIĞINDA (Soft Delete) - Çevirileri de Çöpe At
        // ==========================================================
        static::deleted(function ($page) {
            // Eğer kalıcı olarak SİLİNMİYORSA (sadece çöpe atılıyorsa) ve bu bir ANA SAYFA ise
            if (!$page->isForceDeleting() && empty($page->translation_of)) {
                $translations = self::where('translation_of', $page->id)->get();
                foreach ($translations as $translation) {
                    $translation->delete(); // Çevirinin de deleted_at alanını doldur
                }
            }
        });

        // ==========================================================
        // YENİ: GERİ YÜKLENDİĞİNDE (Restore) - Çevirileri de Geri Yükle
        // ==========================================================
        static::restored(function ($page) {
            // Eğer ana sayfa çöpten çıkarılıyorsa
            if (empty($page->translation_of)) {
                $translations = self::onlyTrashed()->where('translation_of', $page->id)->get();
                foreach ($translations as $translation) {
                    $translation->restore(); // Çevirinin deleted_at alanını temizle
                }
            }
        });

        // ==========================================================
        // KALICI SİLİNMEDEN ÖNCE (Force Delete) - Çevirileri de Kalıcı Sil
        // ==========================================================
        static::forceDeleting(function ($page) {
            if (empty($page->translation_of)) {
                $translations = self::withTrashed()->where('translation_of', $page->id)->get();
                foreach ($translations as $translation) {
                    $translation->forceDelete();
                }
            }
        });

        // ==========================================================
        // KALICI SİLİNDİKTEN SONRA (Fiziksel Dosya Temizliği)
        // ==========================================================
        static::forceDeleted(function ($page) {
            $commonService = app(\App\Services\CommonService::class);
            $masterPage = !empty($page->translation_of) ? self::withTrashed()->find($page->translation_of) : null;

            $deleteIfNotShared = function ($attribute, $configPath) use ($page, $masterPage, $commonService) {
                if (empty($page->$attribute)) return;
                if ($masterPage && $page->$attribute === $masterPage->$attribute) return;
                $commonService->deleteFile(config($configPath), $page->$attribute);
            };

            $deleteIfNotShared('image', 'constants.page_path');
            $deleteIfNotShared('icon',  'constants.page_path');
            $deleteIfNotShared('file',  'constants.file_path');
            $deleteIfNotShared('link',  'constants.file_path');
            $deleteIfNotShared('ses',   'constants.voice_path');
        });
    }
}
