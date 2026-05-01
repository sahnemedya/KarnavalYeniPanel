<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\ReferenceType;
use App\Services\CommonService;
use Illuminate\Http\JsonResponse;
use App\Services\ReferenceTypeService;
use Illuminate\Http\Request;

class ReferenceTypeController extends Controller
{
    protected ReferenceTypeService $referenceTypeService;

    public function __construct(ReferenceTypeService $referenceTypeService)
    {
        $this->referenceTypeService = $referenceTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $languages = Language::all();
        $referenceTypes = ReferenceType::all();
        return view('cms.reference-types.index', compact('referenceTypes', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = Language::all();
        return view('cms.reference-types.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->referenceTypeService->store($request);
        return redirect()->route('cms.reference-types.index')
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
        $languages = Language::all();
        $referenceTypes = ReferenceType::findOrFail($id);
        return view('cms.reference-types.edit', compact('referenceTypes','languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = $this->referenceTypeService->update($request, $id);
        return redirect()->route('cms.reference-types.index')
            ->with("status", $result['status'])->with("message", $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $result = $this->referenceTypeService->destroy($id);
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

        $languages = Language::all();
        $referenceTypes = ReferenceType::onlyTrashed()->get();
        return view('cms.reference-types.deleted', compact('languages', 'referenceTypes'));
    }
    public function restore(string $id): JsonResponse
    {
        $result = $this->referenceTypeService->restore($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }
    public function forceDelete(string $id)
    {
        $result = $this->referenceTypeService->forceDelete($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

}
