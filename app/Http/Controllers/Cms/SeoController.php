<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeoStoreRequest;
use App\Models\Page;
use App\Models\Seo;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    protected SeoService $seoService;

    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seos = Seo::all();
        return view('cms.seo.index', compact('seos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 1. Adım: Zaten SEO kaydı oluşturulmuş sayfaların ID'lerini alıyoruz.
        // Bu sorgu hafiftir ve her seferinde çalışması, güncel durumu görmemizi sağlar.
        $usedPageIds = Seo::pluck('page_id')->toArray();

        // 2. Adım: Tüm sayfaları Cache'den çekiyoruz.
        // Cache'i bozmuyoruz, çünkü başka yerlerde tüm listeye ihtiyaç olabilir.
        $allPages = Cache::remember('pages', now()->addDay(), function () {
            return Page::all();
        });

        // 3. Adım: Cache'den gelen Collection (Koleksiyon) üzerinde filtreleme yapıyoruz.
        // Veritabanına tekrar gitmeden, eldeki listeden kullanılanları çıkarıyoruz.
        $pages = $allPages->whereNotIn('id', $usedPageIds);

        return view('cms.seo.create', compact('pages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SeoStoreRequest $request)
    {
        $result = $this->seoService->store($request);

        // Yeni kayıt eklendiğinde Cache'in güncellenmesine gerek yok çünkü filtrelemeyi dinamik yapıyoruz.
        // Ancak yine de genel sayfa yapısı değişirse diye burada bir işlem yapmıyoruz,
        // create metodundaki mantık bunu hallediyor.

        return redirect()->route('cms.seos.index')
            ->with('status', $result['status'])
            ->with('message', $result['message']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Edit işleminde durum biraz farklıdır.
        // Kullanıcı düzenleme yaparken, o kayda ait sayfanın da listede görünmesi gerekir.

        $seo = Seo::find($id);

        $allPages = Cache::remember('pages', now()->addDay(), function () {
            return Page::all();
        });

        // Edit sayfasında; Zaten kullanılmış olanları gizle AMA şu an düzenlediğimiz kaydın sayfasını gizleme.
        $usedPageIds = Seo::where('id', '!=', $id)->pluck('page_id')->toArray();

        $pages = $allPages->whereNotIn('id', $usedPageIds);

        return view('cms.seo.edit', compact('pages', 'seo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SeoStoreRequest $request, string $id)
    {
        $result = $this->seoService->update($request, $id);
        return redirect()->route('cms.seos.index')
            ->with('status', $result['status'])
            ->with('message', $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->seoService->destroy($id);
        return response()->json(['status' => $result['status'], 'message' => $result['message']]);
    }
}
