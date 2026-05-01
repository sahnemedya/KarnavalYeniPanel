<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\PressKit;
use App\Services\PressKitService;
use Illuminate\Http\Request;

class PressKitController extends Controller
{

    protected PressKitService $pressKitService;

    public function __construct(PressKitService $pressKitService)
    {
        $this->pressKitService = $pressKitService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pressKits = PressKit::all();
        return view('cms.pressKit.index', compact('pressKits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = Language::all();
        return view('cms.pressKit.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->pressKitService->store($request);
        return redirect()->route('cms.press-kit.index')
            ->with("status", $result['status'])
            ->with("message", $result['message']);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->pressKitService->destroy($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }
}
