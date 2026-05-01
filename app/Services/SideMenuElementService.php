<?php

namespace App\Services;

use App\Models\Blade;
use App\Models\Category;
use App\Models\KarnavalSezonu;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SideMenuElementService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

//    public function index($id)
//    {
//        $status = "success";
//        $message = "Kategori Alt Sayfaları Çekildi.";
//        try {
//            $category = Category::findOrFail($id);
//            $langId = request('lang_id', 1);
//
//            // Eğer istenen dil, kategorinin dilinden farklıysa (Çeviri Kategorisini Bul)
//            if ($category->lang_id != $langId) {
//                $translatedCategory = Category::where('lang_id', $langId)
//                    ->where(function($q) use ($category) {
//                        $q->where('translation_of', $category->id)
//                            ->orWhere('translation_of', $category->translation_of);
//                    })->first();
//
//                if ($translatedCategory) {
//                    $category = $translatedCategory;
//                } else {
//                    return [
//                        "status" => "success",
//                        "message" => "Bu kategorinin seçilen dilde karşılığı bulunamadı.",
//                        "pages" => collect([]),
//                        "category" => $category,
//                        "languages" => Language::where('active', 1)->get()
//                    ];
//                }
//            }
//
//            $pages = Page::where('category_id', $category->id)
//                ->where('lang_id', $langId)
//                ->with('translations')
//                ->orderBy('id', 'desc')
//                ->get();
//
//            $languages = Language::where('active', 1)->get();
//
//            LogService::add("Side Menu Element Service Index", $status, $message);
//
//            return [
//                "status" => $status,
//                "message" => $message,
//                "pages" => $pages,
//                "category" => $category,
//                "languages" => $languages
//            ];
//
//        } catch (\Exception $exception) {
//            $status = "error";
//            $message = "Kategori Alt Sayfaları Çekilemedi.";
//            LogService::add("Side Menu Element Service Index", $status, $message . " => " . $exception->getMessage());
//            return ["status" => $status, "message" => $message];
//        }
//    }
    public function index($id)
    {
        $status = "success";
        $message = "Kategori Alt Sayfaları Çekildi.";
        try {
            $category = Category::findOrFail($id);
            $langId = request('lang_id', 1);

            // Eğer istenen dil, kategorinin dilinden farklıysa (Çeviri Kategorisini Bul)
            if ($category->lang_id != $langId) {
                $translatedCategory = Category::where('lang_id', $langId)
                    ->where(function($q) use ($category) {
                        $q->where('translation_of', $category->id)
                            ->orWhere('translation_of', $category->translation_of);
                    })->first();


                if ($translatedCategory) {
                    $category = $translatedCategory;



                } else {

                    return [
                        "status" => "success",
                        "message" => "Bu kategorinin seçilen dilde karşılığı bulunamadı.",
                        "pages" => collect([]),

                        "category" => $category,
                        "languages" => Language::where('active', 1)->get()
                    ];
                }
            }

            $pages = Page::where('category_id', $category->id)
                ->where('lang_id', $langId)
                ->with('translations')
                ->orderBy('id', 'desc')
                ->get();

            $languages = Language::where('active', 1)->get();

            LogService::add("Side Menu Element Service Index", $status, $message);

            return [
                "status" => $status,
                "message" => $message,
                "pages" => $pages,
                "category" => $category,

                "languages" => $languages
            ];

        } catch (\Exception $exception) {
            $status = "error";
            $message = "Kategori Alt Sayfaları Çekilemedi.";
            LogService::add("Side Menu Element Service Index", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function create($id)
    {
        $status = "success";
        $message = "Kategoriye Alt Sayfa Eklemek İçin Gerekli Veriler Çekildi.";
        try {
            $category = Category::select("id", "name")->where('id', $id)->first();
            $blades = Blade::select("id", "name")->get();
            $languages = Language::select("id", "name")->get();
            $pages = Page::select("id", "title")
                ->where('category_id', $id)
                ->orderBy('is_main', 'desc')
                ->orderBy('title', 'asc')
                ->get();
            LogService::add("Side Menu Element Service Create", $status, $message);
            return [
                "status" => $status,
                "message" => $message,
                "pages" => $pages,
                "category" => $category,
                "blades" => $blades,
                "languages" => $languages
            ];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Kategoriye Alt Sayfa Eklemek İçin Gerekli Veriler Çekilemedi.";
            LogService::add("Side Menu Element Service Create", $status, $message . " => " . $exception->getMessage());
            return [
                "status" => $status,
                "message" => $message,
            ];
        }
    }

    public function store(Request $request)
    {
        $status = "success";
        $message = "Sayfa Kaydedildi";

        try {
            $page = Page::create([
                'hit' => $request->hit,
                'sezon_id' => $request->sezon_id,
                'title' => $request->title,
                'inside_title' => $request->inside_title,
                'slug' => $request->slug,
                'content' => $request->content_text,
                'category_id' => $request->category_id,
                "parent_page" => $request->parent_page,
                'blade_id' => $request->blade_id,
                'translation_of' => $request->translation_of,
                'lang_id' => $request->lang_id,
                'is_main' => 0
            ]);

            // 1. Yeni resim yüklendi mi?
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $fileName = $page->slug . '.' . $extension;
                $this->commonService->uploadFile(config('constants.page_path'), $imageFile, $fileName);
                $page->update(["image" => $fileName]);
                LogService::add("Side Menu Element Service Store", "success", $page->title . ' Sayfa Resmi Kaydedildi');
            }
            // 2. Yeni resim yoksa ama bu bir ÇEVİRİ ise (Kaynak resmini kopyala)
            elseif ($request->translation_of) {
                $sourcePage = Page::find($request->translation_of);
                if ($sourcePage && $sourcePage->image) {
                    $page->update(["image" => $sourcePage->image]);
                }
            }
            if ($request->hasFile('icon')) {
                $imageFile = $request->file('icon');
                $extension = $imageFile->guessExtension();
                $fileName = $page->slug . '-icon.' . $extension;
                $this->commonService->uploadFile(config('constants.page_path'), $imageFile, $fileName);
                $page->update(["icon" => $fileName]);
                LogService::add("Side Menu Element Service Store", "success", $page->title . ' Sayfaya İcon Kaydedildi');
            }  // 2. Yeni resim yoksa ama bu bir ÇEVİRİ ise (Kaynak resmini kopyala)
            elseif ($request->translation_of) {
                $sourcePage = Page::find($request->translation_of);
                if ($sourcePage && $sourcePage->icon) {
                    $page->update(["icon" => $sourcePage->icon]);
                }
            }

            // Yönlendirme ID'si (Ana Kategoriye dönmesi için)
            $currentCategory = Category::find($page->category_id);
            $redirectCategoryId = ($currentCategory && $currentCategory->translation_of)
                ? $currentCategory->translation_of
                : $page->category_id;

            return [
                "status" => $status,
                "message" => $message,
                "category" => $page->category_id,
                "lang_id" => $page->lang_id,
                "redirect_category_id" => $redirectCategoryId
            ];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Kaydedilemedi";
            LogService::add("Side Menu Element Service Store ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function edit($categoryId, $pageId)
    {
        $status = "success";
        $message = "Kategori Alt Sayfa Bilgileri Çekildi";

        try {
            $category = Category::findOrFail($categoryId);
            $categories = Category::orderBy('name', 'asc')->get();
            $page = Page::findOrFail($pageId);
            $blades = Blade::select("id", "name")->get();

            // Eğer çeviri ise dil listesinden Türkçeyi çıkar
            if ($page->translation_of) {
                $languages = Language::select("id", "name")->where('id', '!=', 1)->get();
            } else {
                $languages = Language::select("id", "name")->get();
            }

            // Çeviri kaynağı seçimi için SADECE Türkçe sayfalar
            $sourcePagesList = Page::select("id", "title")->where('lang_id', 1)->orderBy('title', 'asc')->get();

            // Kaynak sayfa nesnesi
            $sourcePage = null;
            if ($page->translation_of) {
                $sourcePage = Page::find($page->translation_of);
            }

            $pages = Page::select("id", "title")
                ->where("id", "!=", $category->id)
                ->where("category_id", $categoryId)
                ->orderBy('is_main', 'desc')
                ->orderBy('title', 'asc')
                ->get();

            LogService::add("Side Menu Element Service Edit", $status, $message);
            return [
                "status" => $status,
                "message" => $message,
                "category" => $category,
                "categories" => $categories,
                "page" => $page,
                "sourcePage" => $sourcePage,
                "sourcePagesList" => $sourcePagesList,
                "pages" => $pages,
                "blades" => $blades,
                "languages" => $languages
            ];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Kategori Alt Sayfa Bilgileri Çekilemedi";
            LogService::add("Side Menu Element Service Edit", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function update(Request $request, $pageId)
    {
        $status = "success";
        $message = "Kategori Alt Sayfası Güncellendi";

        try {
            $page = Page::findOrFail($pageId);
            $category = Category::findOrFail($request->category_id);

            // ======================================================
            // 1. IMAGE İŞLEMLERİ (Sayfa Resmi)
            // ======================================================
            if ($request->has('remove_image')) {
                if ($page->image) {
                    $this->commonService->deleteFile(config('constants.page_path'), $page->image);
                }
                $page->update(["image" => NULL]);
            }

            if ($request->hasFile('image')) {
                if ($page->image) {
                    $this->commonService->deleteFile(config('constants.page_path'), $page->image);
                }
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $fileName = $page->slug . '-' . Str::lower(Str::random(4)) . '.' . $extension;
                $this->commonService->uploadFile(config('constants.page_path'), $imageFile, $fileName);
                $page->update(["image" => $fileName]);
            }

            // Slug değişirse resim adı güncelleme
            if (!$request->has('remove_image') && !$request->hasFile('image') && $page->image) {
                if ($request->slug != $page->slug) {
                    $extension = pathinfo($page->image, PATHINFO_EXTENSION);
                    $newImageName = $request->slug . '-' . Str::lower(Str::random(4)) . '.' . $extension;
                    $this->commonService->renameFile(config('constants.page_path'), $page->image, $newImageName);
                    $page->update(["image" => $newImageName]);
                }
            }

            // ======================================================
            // 2. ICON İŞLEMLERİ (Sayfa İconu)
            // ======================================================
            if ($request->has('remove_icon')) {
                if ($page->icon) {
                    $this->commonService->deleteFile(config('constants.page_path'), $page->icon);
                }
                $page->update(["icon" => NULL]);
            }

            if ($request->hasFile('icon')) {
                if ($page->icon) {
                    $this->commonService->deleteFile(config('constants.page_path'), $page->icon);
                }
                $iconFile = $request->file('icon');
                $extension = $iconFile->guessExtension();
                $fileName  = $page->slug . '-icon-' . Str::lower(Str::random(4)) . '.' . $extension;
                $this->commonService->uploadFile(config('constants.page_path'), $iconFile, $fileName);
                $page->update(["icon" => $fileName]);
            }

            if (!$request->has('remove_icon') && !$request->hasFile('icon') && $page->icon) {
                if ($request->slug != $page->slug) {
                    $extension = pathinfo($page->icon, PATHINFO_EXTENSION);
                    $newIconName = $request->slug . '-icon-' . Str::lower(Str::random(4)) . '.' . $extension;
                    $this->commonService->renameFile(config('constants.page_path'), $page->icon, $newIconName);
                    $page->update(["icon" => $newIconName]);
                }
            }


            // ======================================================
            // METİN VE İLİŞKİSEL VERİLERİN GÜNCELLENMESİ
            // ======================================================
            $page->update([
                "title"          => $request->title,
                "inside_title"   => $request->inside_title,
                "slug"           => $request->slug,
                "content"        => $request->content_text,
                "blade_id"       => $request->blade_id,
                "category_id"    => $request->category_id,
                "translation_of" => $request->translation_of,
                "parent_page"    => $request->parent_page,
                "lang_id"        => $request->lang_id,

                // Eklenen yeni alanlar
                "hit"            => $request->hit,
                "sezon_id"       => $request->sezon_id,
            ]);

            LogService::add("Side Menu Element Service Update", $status, $message);

            // Yönlendirme ID'si
            $currentCategory = Category::find($request->category_id);
            $redirectCategoryId = ($currentCategory && $currentCategory->translation_of)
                ? $currentCategory->translation_of
                : $request->category_id;

            return [
                "status" => $status,
                "message" => $message,
                "category" => $category,
                "lang_id" => $page->lang_id,
                "redirect_category_id" => $redirectCategoryId
            ];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Kategori Alt Sayfası Güncellenemedi";
            LogService::add("Side Menu Element Service Update ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function showHomePage($id)
    {
        $status = "success";
        $message = "Anasayfaya Eklendi";
        try {
            $page = Page::findOrFail($id);
            if ($page->show_homepage == 1) {
                $page->update(["show_homepage" => 0]);
                $message = $page->title." Anasayfadan Kaldırıldı.";
            } else {
                $page->update(["show_homepage" => 1]);
                $message = $page->title." Anasayfaya Eklendi";
            }
            LogService::add("Side Menu Publish", $status, $page->title . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = $page->title." Anasayfadan Kaldırıldı.";
            LogService::add("Side Menu Service Publish", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function createLanguage($categoryId, $pageId)
    {
        $status = "success";
        $message = "Çeviri Sayfası Hazırlandı.";
        try {
            $sourcePage = Page::findOrFail($pageId);
            $category = Category::findOrFail($categoryId);

            $existingLangIds = $sourcePage->translations()->pluck('lang_id')->toArray();
            $existingLangIds[] = $sourcePage->lang_id;

            $languages = Language::whereNotIn('id', $existingLangIds)->where('active', 1)->get();
            $blades = Blade::select("id", "name")->get();

            return [
                "status" => $status,
                "message" => $message,
                "category" => $category,
                "sourcePage" => $sourcePage,
                "languages" => $languages,
                "blades" => $blades
            ];

        } catch (\Throwable $exception) {
            return ["status" => "error", "message" => "Veri çekilemedi: " . $exception->getMessage()];
        }
    }
    public function extraStoreUpdate(Request $request, string $id)
    {
        $status = "success";
        $message = "Sayfanın Extra Alanı Güncellendi";
        try {
            $page = Page::findOrFail($id);
            $updateData = [
                'link2' => $request->link2,
                'link3' => $request->link3,
                'video' => $request->video,
                'spotify' => $request->spotify,
                'heyzen' => $request->heyzen,
                'location' => $request->location,
            ];

            // 1. FILE İşlemi
            if ($request->has('remove_file')) {
                if ($page->file) $this->commonService->deleteFile(config('constants.file_path'), $page->file);
                $updateData["file"] = NULL;
            }
            if ($request->filled('uploaded_file')) {
                if ($page->file) $this->commonService->deleteFile(config('constants.file_path'), $page->file);
                $updateData["file"] = $request->uploaded_file;
            }

            // 2. LINK İşlemi
            if ($request->has('remove_link')) {
                if ($page->link) $this->commonService->deleteFile(config('constants.file_path'), $page->link);
                $updateData["link"] = NULL;
            }
            if ($request->filled('uploaded_link')) {
                if ($page->link) $this->commonService->deleteFile(config('constants.file_path'), $page->link);
                $updateData["link"] = $request->uploaded_link;
            }

            // 3. SES İşlemi
            if ($request->has('remove_ses')) {
                if ($page->ses) $this->commonService->deleteFile(config('constants.voice_path'), $page->ses);
                $updateData["ses"] = NULL;
            }
            if ($request->filled('uploaded_ses')) {
                if ($page->ses) $this->commonService->deleteFile(config('constants.voice_path'), $page->ses);
                $updateData["ses"] = $request->uploaded_ses;
            }

            $page->update($updateData);
            LogService::add("Side Menu Element Extra Service Update", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Hata: " . $exception->getMessage();
            LogService::add("Side Menu Element Extra Service Update", $status, $message);
            return ["status" => $status, "message" => $message];
        }
    }

    public function showMenu($id)
    {
        $status = "success";
        $message = "Menüye Eklendi";
        try {
            $page = Page::findOrFail($id);
            if ($page->show_menu == 1) {
                $page->update(["show_menu" => 0]);
                $message = $page->title." Menüden Kaldırıldı.";
            } else {
                $page->update(["show_menu" => 1]);
                $message = $page->title." Menüye Eklendi";
            }
            LogService::add("Side Menu Show Menu", $status, $page->title . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = $page->title." Menüden Kaldırıldı.";
            LogService::add("Side Menu Service Show Menu", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function getDataByLang(Request $request)
    {
        try {
            $langId = $request->lang_id;
            $categories = Category::where('lang_id', $langId)->select('id', 'name')->get();
            $pages = Page::where('lang_id', $langId)->select('id', 'title')->get();

            return [
                'status' => 'success',
                'categories' => $categories,
                'pages' => $pages
            ];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function deleted($categoryId)
    {
        $status = "success";
        $message = "Silinen Sayfalar Getirildi.";
        try {
            $category = Category::findOrFail($categoryId);
            $langId = request('lang_id', 1);

            if ($category->lang_id != $langId) {
                $translatedCategory = Category::where('lang_id', $langId)
                    ->where(function($q) use ($category) {
                        $q->where('translation_of', $category->id)
                            ->orWhere('translation_of', $category->translation_of);
                    })->first();

                if ($translatedCategory) {
                    $category = $translatedCategory;
                } else {
                    return [
                        "status" => "success",
                        "message" => "Bu dilde silinen kayıt bulunamadı.",
                        "pages" => collect([]),
                        "category" => $category
                    ];
                }
            }

            $pages = Page::onlyTrashed()
                ->where("category_id", $category->id)
                ->where("lang_id", $langId)
                ->with('originalPage')
                ->orderBy('deleted_at', 'desc')
                ->get();

            LogService::add("Side Menu Element Service Deleted", $status, $message);

            return [
                'category' => $category,
                'pages' => $pages,
                'status' => $status,
                'message' => $message
            ];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Silinen sayfalar getirilemedi.";
            LogService::add("Side Menu Element Service Deleted", $status, $message . " => " . $exception->getMessage());
            return ['category' => Category::find($categoryId), 'pages' => collect([]), 'status' => $status, 'message' => $message];
        }
    }
}
