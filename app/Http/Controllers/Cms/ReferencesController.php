<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\KarnavalSezonu;
use App\Models\Language;
use App\Models\References;
use App\Models\ReferenceType;
use App\Services\CommonService;
use App\Services\ReferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReferencesController extends Controller
{
    protected ReferenceService $referenceService;

    public function __construct(ReferenceService $referenceService)
    {
        $this->referenceService = $referenceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karnavalSezonlari=KarnavalSezonu::all();
        $references = References::all();
        $referenceTypes = ReferenceType::all();
        return view('cms.references.index', compact('references', 'referenceTypes','karnavalSezonlari'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karnavalSezonlari=KarnavalSezonu::all();
        $languages = Language::all();
        $refaceTypes = ReferenceType::all();
        return view('cms.references.create', compact('languages', 'refaceTypes','karnavalSezonlari'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->referenceService->store($request);
        return redirect()->route('cms.references.index')
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
        $karnavalSezonlari=KarnavalSezonu::all();
        $languages = Language::all();
        $refaceTypes = ReferenceType::all();
         $references = References::findOrFail($id);
        return view('cms.references.edit', compact('references','languages', 'refaceTypes','karnavalSezonlari'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = $this->referenceService->update($request, $id);
        return redirect()->route('cms.references.index')
            ->with("status", $result['status'])->with("message", $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $result = $this->referenceService->destroy($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }
    /**
     * Silinen kayıtları listeler
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function deleted()
    {
        $karnavalSezonlari=KarnavalSezonu::all();
        $languages = Language::all();
        $refaceTypes = ReferenceType::all();
        $references = References::onlyTrashed()->get();
        return view('cms.references.deleted', compact('references','languages', 'refaceTypes','karnavalSezonlari'));
    }
    public function restore(string $id): JsonResponse
    {
        $result = $this->referenceService->restore($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }
    public function forceDelete(string $id)
    {
        $result = $this->referenceService->forceDelete($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }
    public function publish($id)
    {
        $result = $this->referenceService->publish($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }
    public function showHomePage($id)
    {
        $result = $this->referenceService->showHomePage($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    public function bulkCreate()
    {
        $karnavalSezonlari = KarnavalSezonu::all();
        $refaceTypes = ReferenceType::all();
        $languages = Language::where('active', 1)->get();

        return view('cms.references.bulk-create', compact(
            'karnavalSezonlari', 'refaceTypes', 'languages'
        ));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'type_id' => 'required',
            'lang_id' => 'required',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp,svg|max:5120',
        ]);

        $result = $this->referenceService->bulkStore($request);

        if ($result['status'] === 'success') {
            return redirect()->route('cms.references.index')
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message'])->withInput();
    }
}
