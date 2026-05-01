<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Page;
use App\Services\GalleryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    protected GalleryService $galleryService;

    public function __construct(GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::select('page_id')->distinct()->get();
        return view('cms.galleries.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($pageId)
    {
        $result = $this->galleryService->create($pageId);
        if ($result["status"] == "error") {
            return redirect()->route('cms.gallery.index');
        } else {
            $page = $result["page"];
            return view('cms.galleries.create', compact("page"));
        }


    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->galleryService->store($request);
        return response()->json([
            "status" => $result["status"],
            "message" => $result["message"]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $galleryId)
    {
        $result = $this->galleryService->destroy($galleryId);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    /**
     * @param string $pageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyPageGallery(string $pageId)
    {
        $result = $this->galleryService->destroyPageGallery($pageId);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    /**
     * @param $pageId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|object
     */
    public function pageGallery($pageId)
    {
        $result = $this->galleryService->pageGallery($pageId);
        if ($result['status'] == 'success') {
            $galleries = $result['galleries'];
            $page = $result['page'];
            return view('cms.galleries.page-gallery', compact('galleries', 'page'));
        } else {
            return redirect()->route('cms.gallery.index')
                ->with('status', $result['status'])
                ->with('message', $result['message']);
        }

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }
}
