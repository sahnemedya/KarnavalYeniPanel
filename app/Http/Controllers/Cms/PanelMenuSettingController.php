<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CommonService;
use App\Services\PanelMenuSettingService;
use Illuminate\Http\Request;

class PanelMenuSettingController extends Controller
{
    protected PanelMenuSettingService $panelMenuSettingService;
    public function __construct(PanelMenuSettingService $panelMenuSettingService){
        $this->panelMenuSettingService = $panelMenuSettingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::select("id", "name","show_panel")->get();
        return view('cms.settings.panel-menu-setting', compact('categories'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = $this->panelMenuSettingService->update($id);
        return response()->json([
            'status' => $result["status"],
            'message' => $result["message"],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
