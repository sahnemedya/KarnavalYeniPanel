<?php

namespace App\Providers;

use App\Models\ApiKeys;
use App\Models\Blade;
use App\Models\Category;
use App\Models\Contacts;
use App\Models\KarnavalSezonu;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Pagination\Paginator;
use App\Models\Seo;
use App\Models\SiteSettings;
use App\Observers\BladeObserver;
use App\Observers\CategoryObserver;
use App\Observers\LanguageObserver;
use App\Observers\PageObserver;
use App\Observers\SeoObserve;
use App\Observers\SiteSettingsObserver;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade as BladeFacade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        /*
         * --------------------------------------------------------------------------
         * 1. DİL AYARLARI (YENİ EKLENDİ)
         * --------------------------------------------------------------------------
         */
        View::composer('*', function ($view) {
            $activeLanguages = Cache::remember('active_languages', 3600, function () {
                return Language::where('active', 1)->orderBy('id', 'asc')->get();
            });

            $currentLangCode = Session::get('uLang', App::getLocale());
            $currentLang = $activeLanguages->where('code', $currentLangCode)->first();

            if (!$currentLang) {
                $currentLang = $activeLanguages->first();
            }

            if (!app()->runningInConsole()) {
                View::composer('*', function ($view) {
                    $view->with('languages', Language::where('active', 1)->get());
                });
            }

            $view->with('activeLanguages', $activeLanguages);
            $view->with('currentLang', $currentLang);
        });


        /*
         * --------------------------------------------------------------------------
         * 2. CMS (PANEL) HEADER
         * --------------------------------------------------------------------------
         */
        View::composer('cms.partial.header', function ($view) {
            $specialMenus = Category::where("show_panel", true)->orderBy("name")->get();
            $view->with('specialMenus', $specialMenus);
        });

        /*
         * --------------------------------------------------------------------------
         * 3. USER (FRONTEND) HEADER & HEAD - MENÜLER
         * --------------------------------------------------------------------------
         */
        View::composer(['user.partial.header', 'user.partial.head'], function ($view) {
            $currentLangCode = Session::get('uLang', App::getLocale());
            $activeLanguages = Cache::remember('active_languages', 3600, function () {
                return Language::where('active', 1)->orderBy('id', 'asc')->get();
            });

            $currentLang = $activeLanguages->where('code', $currentLangCode)->first();
            $currentLangId = $currentLang ? $currentLang->id : 1;

            $navbarMenus = Category::with([
                // 1. Kategoriye bağlı ANA SAYFA (yönlendirme için → published)
                //    Ana sayfanın altındaki çocuklar MENÜ ÖĞESİ olduğu için → show_menu
                'page' => function ($query) use ($currentLangId) {
                    $query->where('published', 1) // ← Ana sayfa için published
                    ->where('lang_id', $currentLangId)
                        ->with(['children' => function ($q) use ($currentLangId) {
                            $q->where('show_menu', 1) // ← Menüdeki alt sayfalar için show_menu
                            ->where('lang_id', $currentLangId)
                                ->orderBy('hit', 'asc')
                                ->orderBy('id', 'asc')
                                ->with(['children' => function ($subQ) use ($currentLangId) {
                                    $subQ->where('show_menu', 1)
                                        ->where('lang_id', $currentLangId)
                                        ->orderBy('hit', 'asc')
                                        ->orderBy('id', 'asc');
                                }]);
                        }]);
                },
                // 2. Alt Kategoriler (CATEGORIES - Dokunulmadı)
                'children' => function ($query) use ($currentLangId) {
                    $query->where('show_menu', 1)
                        ->where('lang_id', $currentLangId);
                },
                // 3. Alt Kategorilerin ANA SAYFASI (yönlendirme için → published)
                'children.page' => function ($query) use ($currentLangId) {
                    $query->where('published', 1) // ← Ana sayfa için published
                    ->where('lang_id', $currentLangId)
                        ->with(['children' => function ($q) use ($currentLangId) {
                            $q->where('show_menu', 1) // ← Menüdeki alt sayfalar için show_menu
                            ->where('lang_id', $currentLangId)
                                ->orderBy('hit', 'asc')
                                ->orderBy('id', 'asc')
                                ->with(['children' => function ($subQ) use ($currentLangId) {
                                    $subQ->where('show_menu', 1)
                                        ->where('lang_id', $currentLangId)
                                        ->orderBy('hit', 'asc')
                                        ->orderBy('id', 'asc');
                                }]);
                        }]);
                },
                // 4. Direkt Kategoriye bağlı sayfalar (MENÜDE gösterilenler → show_menu)
                'subPages' => function ($query) use ($currentLangId) {
                    $query->where('show_menu', 1)
                        ->where('lang_id', $currentLangId)
                        ->whereNull('parent_page')
                        ->orderBy('hit', 'asc')
                        ->orderBy('id', 'asc');
                }
            ])
                ->whereNull("parent_category")
                ->where('show_menu', 1)
                ->where('lang_id', $currentLangId)
                ->orderBy("hit", "asc")
                ->orderBy("id", "asc")
                ->get();

            $navbarMenus->each(function ($category) use ($currentLangId) {
                if (!$category->page || !$category->page->isMainPageOfCategory()) {
                    $matchingPage = \App\Models\Page::where('title', $category->name)
                        ->where('published', 1) // ← Ana sayfa fallback için published
                        ->where('category_id', $category->id)
                        ->where('lang_id', $currentLangId)
                        ->first();

                    if ($matchingPage && $matchingPage->isMainPageOfCategory()) {
                        $category->setRelation('page', $matchingPage);
                    } else {
                        $category->setRelation('page', null);
                    }
                }
            });

            $view->with('navbarMenus', $navbarMenus);
        });


        /*
         * --------------------------------------------------------------------------
         * 4. SIDE MENU (SOL MENÜ)
         * --------------------------------------------------------------------------
         */
        View::composer('user.partial.sidemenu', function ($view) {
            $currentSlug = Request::segment(1);
            $navbarMenus = collect();
            $currentPage = null;
            $activeCategory = null;

            if ($currentSlug) {
                $currentPage = \App\Models\Page::where('slug', $currentSlug)
                    ->where('published', 1)
                    ->first();

                if ($currentPage) {
                    if ($currentPage->category_id) {
                        $activeCategory = Category::find($currentPage->category_id);
                    }
                    if (!$activeCategory) {
                        $activeCategory = Category::whereHas('page', function ($query) use ($currentPage) {
                            $query->where('id', $currentPage->id);
                        })->first();
                    }
                    if (!$activeCategory) {
                        $activeCategory = Category::whereHas('subPages', function ($query) use ($currentPage) {
                            $query->where('id', $currentPage->id);
                        })->first();
                    }
                    if (!$activeCategory && $currentPage->parent_page) {
                        $parentPage = \App\Models\Page::find($currentPage->parent_page);
                        if ($parentPage) {
                            if ($parentPage->category_id) {
                                $activeCategory = Category::find($parentPage->category_id);
                            } else {
                                $activeCategory = Category::whereHas('page', function ($query) use ($parentPage) {
                                    $query->where('id', $parentPage->id);
                                })->orWhereHas('subPages', function ($query) use ($parentPage) {
                                    $query->where('id', $parentPage->id);
                                })->first();
                            }
                        }
                    }

                    if ($activeCategory) {
                        $matchingPage = \App\Models\Page::where('title', $activeCategory->name)
                            ->where('published', 1)
                            ->first();

                        if ($matchingPage && !$activeCategory->page) {
                            $activeCategory->setRelation('page', $matchingPage);
                        }

                        $activeCategory->load(['subPages' => function ($query) {
                            $query->where('published', 1)
                                ->whereNull('parent_page')
                                ->orderBy('id', 'asc');
                        }]);

                        $navbarMenus = collect([$activeCategory]);
                    }
                }
            }

            $view->with([
                'navbarMenus' => $navbarMenus,
                'currentPage' => $currentPage,
                'currentSlug' => $currentSlug
            ]);
        });

        /*
         * --------------------------------------------------------------------------
         * 5. FOOTER KATEGORİLERİ
         * --------------------------------------------------------------------------
         */
        /*
  * --------------------------------------------------------------------------
  * 5. FOOTER KATEGORİLERİ
  * --------------------------------------------------------------------------
  */
//        View::composer('user.partial.footer', function ($view) {
//            $activeLangId = Session::get('uLangId') ?? 1;
//            $targetKurumsalCatId = 2;
//
//            if ($activeLangId != 1) {
//                $translatedCat = Category::where('translation_of', 2)
//                    ->where('lang_id', $activeLangId)
//                    ->first();
//                if ($translatedCat) {
//                    $targetKurumsalCatId = $translatedCat->id;
//                }
//            }
//            // Footer'da gösterilecek kategorileri çek
//            $footerCategories = Category::where('show_footer', 1)
//                // BURADA DÜZELTME YAPILDI: "use ($activeLangId)" eklendi
//                ->with(['allPages' => function ($query) use ($activeLangId) {
//                    // Artık $activeLangId burada kullanılabilir
//                    $query->where('show_footer', 1)
//                        ->where('lang_id', $activeLangId)
//                        ->where('published', 1)
//                        ->orderBy('id', 'asc');
//                }])
//                ->orderBy('id', 'asc')
//                ->get();
//
//            // View'e gönder
//            $view->with('footerCategories', $footerCategories);
//        });

        View::composer('user.partial.footer', function ($view) {
            // 1. Aktif Dili Al
            $activeLangId = Session::get('uLangId') ?? 1;

            // ---------------------------------------------------------------------
            // A) KURUMSAL SAYFALAR (Category ID: 2)
            // ---------------------------------------------------------------------
            $targetKurumsalCatId = 1;

            if ($activeLangId != 1) {
                $translatedCat = Category::where('translation_of', 1)
                    ->where('lang_id', $activeLangId)
                    ->first();
                if ($translatedCat) {
                    $targetKurumsalCatId = $translatedCat->id;
                }
            }

            $footerKurumsal = Page::where('category_id', $targetKurumsalCatId)
                ->where('lang_id', $activeLangId)
                ->where('show_footer', 1)
                ->where('published', 1)
                ->orderBy('id', 'asc')
                ->get();

            // ---------------------------------------------------------------------
            // B) TEDAVİ ALANLARI (Category ID: 3) - (Eski Hizmetler Alanı)
            // ---------------------------------------------------------------------
            $targetTedaviCatId = 4; // Varsayılan (Türkçe) Kategori ID'si

            if ($activeLangId != 1) {
                // ID'si 3 olan kategorinin o dildeki karşılığını bul
                $translatedTedaviCat = Category::where('translation_of', 4)
                    ->where('lang_id', $activeLangId)
                    ->first();

                if ($translatedTedaviCat) {
                    $targetTedaviCatId = $translatedTedaviCat->id;
                }
            }

            // Hizmetlerdeki 'parent_page' yerine burada 'category_id' kullandık
            $footerTedaviAlanlari = Page::where('category_id', $targetTedaviCatId)
                ->where('lang_id', $activeLangId)
                ->where('show_footer', 1)
                ->where('published', 1)
                ->orderBy('id', 'asc')
                ->get();

            // ---------------------------------------------------------------------

            // Değişkenleri view'a gönderiyoruz
            $view->with([
                'footerKurumsal'       => $footerKurumsal,
                'footerTedaviAlanlari' => $footerTedaviAlanlari // View'da değişken adı değişti
            ]);
        });

        view()->composer('user.partial.footer', function ($view) {
            // 1. Session'dan dili alıp Carbon'a set ediyoruz (Tarihlerin çevrilmesi için)
            $activeLangId = Session::get('uLangId') ?? 1;
            $locale = match((int)$activeLangId) {
                2 => 'en',
                3 => 'fr',
                default => 'tr',
            };
            Carbon::setLocale($locale);

            $aktifSezon = KarnavalSezonu::where('published', 1)->first();
            $formatliTarih = '';

            if ($aktifSezon) {
                $baslangic = Carbon::parse($aktifSezon->karnaval_tarihi_baslangic);
                $bitis = Carbon::parse($aktifSezon->karnaval_tarihi_bitis);

                // translatedFormat('F') artık Session'daki dile göre çalışacak
                $formatliTarih = $baslangic->day . ' - ' . $bitis->day . ' ' . $bitis->translatedFormat('F') . ' ' . $bitis->year;
            } else {
                // 2. Aktif sezon yoksa: Mevcut yıla +1 ekliyoruz
                $nextYear = Carbon::now()->addYear()->year;

                // Mesajı da dile göre dinamik yapıyoruz
                $formatliTarih = match((int)$activeLangId) {
                    2 => "SEE YOU IN $nextYear!",
                    3 => "RENDEZ-VOUS EN $nextYear!",
                    default => "$nextYear'DA GÖRÜŞMEK ÜZERE!",
                };
            }

            $view->with('formatliTarih', $formatliTarih);
        });



        View::composer(['user.partial.header', 'user.partial.footer', 'user.partial.head'], function ($view) {
            $contacts = Contacts::first();
            $view->with('contacts', $contacts);
        });

        View::composer(['user.partial.header', 'user.partial.footer', 'user.partial.head', 'user.index'], function ($view) {
            $siteSetting = SiteSettings::first();
            $view->with('siteSetting', $siteSetting);
        });


//        MAİL KEYS ÇEKME
        View::composer(['user.blades.iletisim', 'user.blades.insan-kaynaklari', 'user.blades.video', 'user.blades.randevu-al','user.blades.normal-sayfa-iletisim-formu', 'user.index'], function ($view) {
            $apiKeys = ApiKeys::first();
            $view->with('apiKeys', $apiKeys);
        });


        BladeFacade::directive('permission', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$permission})): ?>";
        });

        BladeFacade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });

        View::addLocation(storage_path('app/public/views'));
        Blade::observe(BladeObserver::class);
        Language::observe(LanguageObserver::class);
        Category::observe(CategoryObserver::class);
        Page::observe(PageObserver::class);
        SiteSettings::observe(SiteSettingsObserver::class);
        Seo::observe(SeoObserve::class);


    }
}
