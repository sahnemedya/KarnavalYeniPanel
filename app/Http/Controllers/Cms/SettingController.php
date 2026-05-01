<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use App\Services\SiteSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    protected SiteSettingsService $siteSettingsService;

    public function __construct(SiteSettingsService $siteSettingsService)
    {
        $this->siteSettingsService = $siteSettingsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siteSettings = Cache::remember('siteSettings', 3600, function () {
            return SiteSettings::first();
        });
        return view('cms.settings.site', compact('siteSettings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = $this->siteSettingsService->update($request, $id);
        return redirect()->route("cms.settings.site.index")
            ->with("status", $result["status"])
            ->with("message", $result["message"]);

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
