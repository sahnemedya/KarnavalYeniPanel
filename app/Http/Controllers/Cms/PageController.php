<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageStoreRequest;
use App\Http\Requests\PageUpdateRequest;
use App\Models\Blade;
use App\Models\Category;
use App\Models\KarnavalSezonu;
use App\Models\Language;
use App\Models\Page;
use App\Services\PageService;
use App\Services\CommonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PageController extends Controller
{
    protected PageService $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karnavalSezonlari = KarnavalSezonu::all();
        $pages = Page::where('is_main',1)->get();
        return view('cms.pages.index', compact('pages','karnavalSezonlari'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select("id", "name")->get();
        $karnavalSezonlari = KarnavalSezonu::all();
        $blades = Blade::select("id", "name")->get();
        $languages = Language::select("id", "name")->get();
        $pages = Page::select("id", "title")->get();
        return view('cms.pages.create', compact('categories','karnavalSezonlari', 'blades', 'languages', 'pages'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageStoreRequest $request)
    {
        $result = $this->pageService->store($request);
        return redirect()->route('cms.pages.index')->with("status", $result['status'])->with("message", $result['message']);
    }

    public function extraedit(Request $request, string $id)
    {
        $page = Page::find($id);
        return view('cms.pages.extra', compact( 'page'));
    }

    public function extraStoreUpdate(Request $request, string $id)
    {
        $result = $this->pageService->extraStoreUpdate($request, $id);
        return redirect()->route('cms.pages.index')->with("status", $result['status'])->with("message", $result['message']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $categories = Category::select("id", "name")->get();
        $blades = Blade::select("id", "name")->get();
        $languages = Language::select("id", "name")->get();
        $pages = Page::select("id", "title")->where("id", "!=", $id)->get();
        $page = Page::find($id);
        $karnavalSezonlari = KarnavalSezonu::all();
        return view('cms.pages.edit', compact('categories', 'blades','karnavalSezonlari', 'languages', 'page', 'pages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageUpdateRequest $request, string $id)
    {
        $result = $this->pageService->update($request, $id);
        return redirect()->route('cms.pages.index')->with("status", $result['status'])->with("message", $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $result = $this->pageService->destroy($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
        ]);
    }

    /**
     * Sayfanın Yayınlanmasını sağlamak için kullanabilirsiniz.
     * @param string $id
     * @return JsonResponse
     */
    public function publishPage(string $id): JsonResponse
    {
        $result = $this->pageService->publishPage($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
        ]);
    }

    public function activate(Request $request, string $id)
    {
        $result = $this->pageService->activate($request, $id);
        return response()->json($result);
    }

    /**
     * Silinen kayıtları listeler
     */
    public function deleted()
    {
        $pages = Page::onlyTrashed()->where("is_main",1)->get();
        return view('cms.pages.deleted', compact('pages'));
    }

    public function restore(string $id): JsonResponse
    {
        $result = $this->pageService->restore($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    public function forceDelete(string $id)
    {
        $result = $this->pageService->forceDelete($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    public function showHomePage($id)
    {
        $result = $this->pageService->showHomePage($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    // =========================================================
    // YENİ EKLENEN ASENKRON METOTLAR (ARKAPLANDA DOSYA YÜKLEME)
    // =========================================================

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

        if($files) {
            foreach ($files as $type => $fileName) {
                if ($fileName) {
                    $path = ($type === 'ses') ? config('constants.voice_path') : config('constants.file_path');
                    $commonService->deleteFile($path, $fileName);
                }
            }
        }
        return response()->json(['status' => 'success']);
    }
}
