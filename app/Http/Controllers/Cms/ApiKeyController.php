<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ApiKeys;
use App\Services\ApiKeyService;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    protected ApiKeyService $apiKeyService;
    public function __construct(ApiKeyService $apiKeyService){
        $this->apiKeyService = $apiKeyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apiKey = ApiKeys::first();
        return view('cms.settings.api-key', compact('apiKey'));
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = $this->apiKeyService->update($request, $id);
        return redirect()->route('cms.settings.api-key.index')
            ->with('status',$result['status'])
            ->with('message',$result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
