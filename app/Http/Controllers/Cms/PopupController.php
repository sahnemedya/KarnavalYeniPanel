<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Popup;
use App\Services\PopupService;
use Illuminate\Http\Request;

class PopupController extends Controller
{

    protected PopupService $popupService;
    public function __construct(PopupService $popupService){
        $this->popupService = $popupService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $popups = Popup::all();
        return view('cms.popup.index',compact('popups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = Language::all();
        return view('cms.popup.create',compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->popupService->store($request);
        return redirect()->route('cms.popup.index')
            ->with('status',$result['status'])
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
        $result = $this->popupService->destroy($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    public function publish($id)
    {
        $result = $this->popupService->publish($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }
}
