<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use App\Models\Page;
class Category extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['name', 'note', 'image', 'hit', 'parent_category', 'translation_of', 'lang_id', 'show_menu', 'show_homepage', 'show_footer', 'show_panel'];

    /**
     * Kategori resimlerinin tutulduğu klasör yolunu getirir
     * @return string
     */
    public function getImagePath()
    {
        return asset('storage/' . Config::get('constants.category_path'));
    }

    /**
     * Kategori resminin yolunu getirir.
     * @return string
     */
    public function getImage()
    {
        return asset('storage/' . Config::get('constants.category_path') . '/' . $this->image);
    }

    /**
     * Kategorinin resmininin yolunu getirir. Eğer resim yoksa false değeri getirir.
     * @return false|string
     */
    public function image()
    {
        if ($this->image) {
            return asset("storage/" . config('constants.category_path') . "/" . $this->image);
        }
        return false;
    }

    /**
     * Bir kategorinin ait olduğu üst kategoriyi getirir.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category');
    }

    /**
     * Bir kategorinin altında yer alan, en üst düzey (ana) kategorileri getirir.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category');
    }

    /**
     * Bir kategorinin altında yer alan tüm alt sayfaları getirir.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allPages()
    {
        return $this->hasMany(Page::class);
    }

    /**
     * Bir kategorinin altında yer alan, en üst düzey (ana) sayfaları getirir.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
        return $this->hasMany(Page::class)->whereNull('parent_page');
    }

    public function page()
    {
        return $this->hasOne(Page::class, 'category_id', 'id')->where('is_main', 1);
    }

    public function subPages()
    {
        return $this->hasMany(Page::class, 'category_id')->where('is_main', 0);
//        Controller Kullanımı Kullanımı: $category->subPages()->get();
//        Blade içerisinde kullanımı: $category->subPages (foreach içerisinde)
    }
    public function getSayfa()
    {
        return $this->hasOne(Page::class, 'category_id', 'id');
//        return $this->hasOne(\App\Models\Page::class, 'category_id', 'id');

    }
    /**
     * Kategorinin herhangi bir sayfasını getirir (is_main durumuna bakmadan)
     */
    public function anyPage()
    {
        return $this->hasOne(Page::class, 'category_id', 'id');
    }

    /**
     * Kategorinin ana sayfasını getirir
     */
    public function mainPage()
    {
        return $this->hasOne(Page::class, 'category_id', 'id')->where('is_main', 1);
    }

    /**
     * Geçerli kategorinin breadcrumb listesini verir
     * @return \Illuminate\Support\Collection
     */
    public function breadcrumbs()
    {
        $breadcrumbs = collect();

        if ($this->parent) {
            $breadcrumbs = $this->parent->breadcrumbs();
        }

        // Kategoriye ait ana sayfa varsa, onu ekle
        if ($this->page) {
            $breadcrumbs->push($this->page);
        } else {
            $breadcrumbs->push($this);
        }

        return $breadcrumbs;
    }


}
