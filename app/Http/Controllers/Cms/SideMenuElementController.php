<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageStoreRequest;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Services\CommonService;
use App\Models\Page;
use App\Models\KarnavalSezonu;

// KarnavalSezonu modeli eklendi
use App\Services\SideMenuElementService;
use Illuminate\Http\Request;
use App\Services\GeminiService;
use App\Services\PageVideoService;

class SideMenuElementController extends Controller
{
    protected SideMenuElementService $sideMenuElementService;
    protected GeminiService $geminiService;

    protected PageVideoService $pageVideoService;

    public function __construct(SideMenuElementService $sideMenuElementService, GeminiService $geminiService, PageVideoService $pageVideoService)
    {
        $this->sideMenuElementService = $sideMenuElementService;
        $this->geminiService = $geminiService;
        $this->pageVideoService = $pageVideoService;
    }

    public function index($id)
    {
        $result = $this->sideMenuElementService->index($id);
        $karnavalSezonlari = KarnavalSezonu::all(); // Karnaval sezonları çekildi

        return view('cms.sideMenuElements.index')->with([
            'category' => $result['category'],
            'pages' => $result['pages'],
            'languages' => $result['languages'] ?? [],
            'karnavalSezonlari' => $karnavalSezonlari, // View'a gönderildi
        ]);
    }

    public function create($id)
    {
        $result = $this->sideMenuElementService->create($id);
        $karnavalSezonlari = KarnavalSezonu::all(); // Karnaval sezonları çekildi

        // Değişkenleri başlat (Varsayılan null)
        $mainPage = null;
        $blogDetailBlade = null;
        $duyuruDetailBlade = null;
        $recipeDetailBlade = null; // Yeni değişken tanımlandı

        if (isset($result['category']) && isset($result['pages']) && isset($result['blades'])) {

            // 1. Haberler & Duyurular (News & Announcements) Kontrolü
            if ($result['category']->name == 'Blog & Duyurular' || $result['category']->name == 'News & Announcements') {
                $mainPage = collect($result['pages'])->where('name', $result['category']->title)->first();
                $blogDetailBlade = collect($result['blades'])->where('name', 'Blog Detay')->first();
            }

            // 2. Duyurular Kontrolü
            if ($result['category']->name == 'Duyurular') {
                $mainPage = collect($result['pages'])->where('name', $result['category']->title)->first();
                $duyuruDetailBlade = collect($result['blades'])->where('name', 'Duyuru Detay')->first();
            }

            // 3. YENİ: Yemek Tarifleri (Recipes) Kontrolü
            if ($result['category']->name == 'Yemek Tarifleri' || $result['category']->name == 'Recipes') {
                // Ana sayfayı bul
                $mainPage = collect($result['pages'])->where('name', $result['category']->title)->first();

                // Yemek Tarifleri Detay blade'ini bul
                $recipeDetailBlade = collect($result['blades'])->where('name', 'Yemek Tarifleri Detay')->first();
            }
        }

        return view('cms.sideMenuElements.create')
            ->with([
                'category' => $result['category'],
                'pages' => $result['pages'],
                'blades' => $result['blades'],
                'languages' => $result['languages'],
                'mainPage' => $mainPage,
                'blogDetailBlade' => $blogDetailBlade,
                'duyuruDetailBlade' => $duyuruDetailBlade,
                'recipeDetailBlade' => $recipeDetailBlade,
                'karnavalSezonlari' => $karnavalSezonlari, // View'a gönderildi
            ]);

        return view('cms.sideMenuElements.create')->with($result);
    }

    public function store(PageStoreRequest $request)
    {
        $result = $this->sideMenuElementService->store($request);

        // Yönlendirme
        $targetId = $result['redirect_category_id'] ?? $request->category_id;
        $routeParams = ['id' => $targetId];
        if (isset($result['lang_id'])) {
            $routeParams['lang_id'] = $result['lang_id'];
        }

        return redirect()->route('cms.side-menu-elements.index', $routeParams)
            ->with(['status' => $result['status'], 'message' => $result['message']]);
    }

    public function edit($categoryId, $pageId)
    {
        $result = $this->sideMenuElementService->edit($categoryId, $pageId);

        if ($result['status'] === "success") {
            // Edit işlemi başarılıysa karnaval sezonlarını $result dizisine dahil ediyoruz
            $result['karnavalSezonlari'] = KarnavalSezonu::all();

            // Eğer çeviri ise özel view
            if ($result['page']->translation_of) {
                return view('cms.sideMenuElements.language-edit')->with($result);
            }
            return view('cms.sideMenuElements.edit')->with($result);
        } else {
            return redirect()->route('cms.side-menu-elements.index', $categoryId)
                ->with(['status' => $result['status'], 'message' => $result['message']]);
        }
    }

    public function update(Request $request, string $pageId)
    {
        $result = $this->sideMenuElementService->update($request, $pageId);

        $targetId = $result['redirect_category_id'] ?? $request->category_id;
        $routeParams = ['id' => $targetId];
        if (isset($result['lang_id'])) {
            $routeParams['lang_id'] = $result['lang_id'];
        }

        if ($result['status'] == "success" && isset($result["category"]) && $result["category"]->show_panel != 1) {
            return redirect()->route('cms.side-menu-elements.index', $routeParams);
        }
        return redirect()->route('cms.side-menu-elements.index', $routeParams)
            ->with(["status", $result['status'], "message", $result['message']]);
    }

    public function deleted($categoryId)
    {
        $result = $this->sideMenuElementService->deleted($categoryId);
        return view('cms.sideMenuElements.language-deleted', [
            'category' => $result['category'],
            'pages' => $result['pages'],
        ]);
    }

    public function showHomePage($id)
    {
        $result = $this->sideMenuElementService->showHomePage($id);
        return response()->json($result);
    }

    public function createLanguage($categoryId, $pageId)
    {
        $result = $this->sideMenuElementService->createLanguage($categoryId, $pageId);
        if ($result['status'] === 'error') {
            return redirect()->back()->with(['status' => 'error', 'message' => $result['message']]);
        }
        $result['karnavalSezonlari'] = KarnavalSezonu::all();
        return view('cms.sideMenuElements.language-create')->with($result);
    }

    public function showMenu($id)
    {
        $result = $this->sideMenuElementService->showMenu($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    public function getDataByLang(Request $request)
    {
        try {
            $langId = $request->lang_id;

            // EŞLEŞTİRME İÇİN 'translation_of' SÜTUNUNU DA ÇEKİYORUZ
            $categories = Category::where('lang_id', $langId)->select('id', 'name', 'translation_of')->get();
            $pages = Page::where('lang_id', $langId)->select('id', 'title', 'translation_of')->get();

            return [
                'status' => 'success',
                'categories' => $categories,
                'pages' => $pages
            ];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    // ==========================================
    // EXTRA SAYFASI VE ASENKRON METOTLAR (YENİ)
    // ==========================================
    public function extraedit(Request $request, string $id)
    {
        $page = Page::find($id);
        // Kullanıcının sayfaya girerken geldiği tam linki (lang_id dahil) alıyoruz
        $previousUrl = url()->previous();
        $videos = $this->pageVideoService->getVideosForPage((int)$id);

        return view('cms.sideMenuElements.extra', compact('page', 'previousUrl', 'videos'));
    }

    public function extraStoreUpdate(Request $request, string $id)
    {
        $result = $this->sideMenuElementService->extraStoreUpdate($request, $id);

        // Eğer formun içinden previous_url geldiyse, direkt o linke (filtrelerle) geri dön
        if ($request->filled('previous_url')) {
            return redirect($request->previous_url)
                ->with("status", $result['status'])
                ->with("message", $result['message']);
        }

        // URL gelmediyse (direkt linkle girildiyse) varsayılan kategori listesine dön
        $page = Page::find($id);
        return redirect()->route('cms.side-menu-elements.index', $page->category_id)
            ->with("status", $result['status'])
            ->with("message", $result['message']);
    }

    public function asyncUpload(Request $request, CommonService $commonService)
    {
        try {
            $file = $request->file('file_data');
            $type = $request->input('type'); // 'file', 'link', 'ses'
            $slug = $request->input('slug');

            $path = ($type === 'ses') ? config('constants.voice_path') : config('constants.file_path');
            $extension = $file->getClientOriginalExtension();
            $fileName = $slug . '-' . $type . '-' . Str::lower(Str::random(4)) . '.' . $extension;

            $commonService->uploadFile($path, $file, $fileName);

            return response()->json(['status' => 'success', 'filename' => $fileName]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    public function asyncDelete(Request $request, CommonService $commonService)
    {
        $files = json_decode($request->input('files'), true);

        if ($files) {
            foreach ($files as $type => $fileName) {
                if ($fileName) {
                    $path = ($type === 'ses') ? config('constants.voice_path') : config('constants.file_path');
                    $commonService->deleteFile($path, $fileName);
                }
            }
        }
        return response()->json(['status' => 'success']);
    }

    public function fetchTranslationFromGemini(Request $request)
    {
        $request->validate([
            'source_page_id' => 'required|integer',
            'target_lang_id' => 'required|integer'
        ]);

        // Kaynak sayfayı (Türkçe) bul
        $sourcePage = Page::find($request->source_page_id);
        if (!$sourcePage) {
            return response()->json(['status' => 'error', 'message' => 'Kaynak sayfa bulunamadı.']);
        }

        // Hedef dili bul
        $targetLang = \App\Models\Language::find($request->target_lang_id);
        $targetLangName = $targetLang ? $targetLang->name : 'English';

        // Servise gönder
        $translation = $this->geminiService->translateContent(
            $sourcePage->title ?? '',
            $sourcePage->content ?? '',
            $targetLangName
        );

        return response()->json([
            'status' => 'success',
            'data' => $translation
        ]);
    }

    public function videoStore(Request $request, string $pageId)
    {
        $result = $this->pageVideoService->store($request, (int)$pageId);
        return response()->json($result);
    }

    /**
     * Video güncelle.
     * PUT cms/side-menu-elements/videos/{videoId}
     */
    public function videoUpdate(Request $request, string $videoId)
    {
        $result = $this->pageVideoService->update($request, (int)$videoId);
        return response()->json($result);
    }

    /**
     * Video sil.
     * DELETE cms/side-menu-elements/videos/{videoId}
     */
    public function videoDestroy(string $videoId)
    {
        $result = $this->pageVideoService->destroy((int)$videoId);
        return response()->json($result);
    }

    /**
     * Sürükle-bırak sıralama (AJAX).
     * POST cms/side-menu-elements/videos/reorder
     * Body: { ordered_ids: [3, 1, 5, 2] }
     */
    public function videoReorder(Request $request)
    {
        $request->validate([
            'ordered_ids' => 'required|array',
            'ordered_ids.*' => 'integer|exists:page_videos,id',
        ]);

        $result = $this->pageVideoService->reorder($request->input('ordered_ids'));
        return response()->json($result);
    }

// --- CHUNKED UPLOAD ---

    /**
     * Yeni chunked upload session başlat.
     * POST cms/side-menu-elements/videos/upload/init
     */
    public function videoUploadInit(Request $request)
    {
        $result = $this->pageVideoService->initChunkedUpload($request);
        return response()->json($result);
    }

    /**
     * Chunk yükle.
     * POST cms/side-menu-elements/videos/upload/{uploadId}/chunk
     */
    public function videoUploadChunk(Request $request, string $uploadId)
    {
        $result = $this->pageVideoService->storeChunk($request, $uploadId);
        return response()->json($result);
    }

    /**
     * Chunk'ları birleştir, final dosyayı oluştur.
     * POST cms/side-menu-elements/videos/upload/{uploadId}/finalize
     */
    public function videoUploadFinalize(Request $request, string $uploadId)
    {
        $result = $this->pageVideoService->finalizeChunkedUpload($request, $uploadId);
        return response()->json($result);
    }

    /**
     * Yarım kalan upload'u iptal et.
     * DELETE cms/side-menu-elements/videos/upload/{uploadId}
     */
    public function videoUploadCancel(string $uploadId)
    {
        $result = $this->pageVideoService->cancelChunkedUpload($uploadId);
        return response()->json($result);
    }

    /**
     * Kapak görseli için async yükleme (mevcut asyncUpload pattern'i ile aynı, küçük dosya).
     * POST cms/side-menu-elements/videos/cover-upload
     */
    public function videoCoverUpload(Request $request)
    {
        $result = $this->pageVideoService->uploadCoverAsync($request);
        return response()->json($result);
    }

    /**
     * Yüklenmiş ama henüz kaydedilmemiş dosyayı sil.
     * POST cms/side-menu-elements/videos/temp-delete
     */
    public function videoTempDelete(Request $request)
    {
        $filename = $request->input('filename');
        if (!$filename) {
            return response()->json(["status" => "error", "message" => "filename eksik"], 422);
        }
        $result = $this->pageVideoService->deleteTempFile($filename);
        return response()->json($result);
    }
}
