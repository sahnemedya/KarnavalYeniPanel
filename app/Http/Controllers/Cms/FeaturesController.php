<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Page;
use App\Services\FeatureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeaturesController extends Controller
{
    protected FeatureService $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }

    public function index(Request $request)
    {
        // Tüm sayfaları dropdown veya filtre için gönderiyoruz
        $pages = Page::all();

        // Eğer linkten page_id geldiyse sadece ona ait özellikleri filtrele
        if ($request->has('page_id') && !empty($request->page_id)) {
            $features = Feature::where('page_id', $request->page_id)->get();

            // Kullanıcı hangi sayfanın özelliklerine baktığını bilsin diye
            $selectedPage = Page::find($request->page_id);

            return view('cms.features.index', compact('pages', 'features', 'selectedPage'));
        }

        // Eğer page_id yoksa, TÜM özellikleri getir (Eski çalışma mantığı)
        $features = Feature::all();
        $selectedPage = null; // Seçili sayfa yok

        return view('cms.features.index', compact('pages', 'features', 'selectedPage'));
    }

    public function create(Request $request, $page_id = null)
    {
        $pages = Page::orderBy('title', 'asc')->get();
        $selectedPage = null;
        $nextHit = 1;

        if ($page_id) {
            $selectedPage = Page::find($page_id);
            if ($selectedPage) {
                $maxHit = Feature::where('page_id', $page_id)->max('hit');
                $nextHit = $maxHit ? $maxHit + 1 : 1;
            }
        }
        return view('cms.features.create', compact('pages', 'nextHit', 'selectedPage'));
    }

    public function store(Request $request)
    {
        $result = $this->featureService->store($request);
        return redirect()->route('cms.features.index')
            ->with('status', $result['status'])
            ->with('message', $result['message']);
    }

    public function edit(string $id)
    {
        $feature = Feature::findOrFail($id);
        $pages = Page::orderBy('title', 'asc')->get();
        return view('cms.features.edit', compact('pages', 'feature'));
    }

    public function update(Request $request, string $id)
    {
        $result = $this->featureService->update($request, $id);
        return redirect()->route('cms.features.index')
            ->with("status", $result['status'])->with("message", $result['message']);
    }

    public function destroy(string $id): JsonResponse
    {
        $result = $this->featureService->destroy($id);
        return response()->json($result);
    }

    public function deleted()
    {
        $features = Feature::onlyTrashed()->get();
        return view('cms.features.deleted', compact('features'));
    }

    public function restore(string $id): JsonResponse
    {
        $result = $this->featureService->restore($id);
        return response()->json($result);
    }

    public function forceDelete(string $id)
    {
        $result = $this->featureService->forceDelete($id);
        return response()->json($result);
    }

    public function publish($id)
    {
        $result = $this->featureService->publish($id);
        return response()->json($result);
    }
}
