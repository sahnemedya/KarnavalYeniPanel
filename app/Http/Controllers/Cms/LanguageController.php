<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageStoreRequest;
use App\Models\Language;
use App\Services\LanguageService;
use App\Services\LogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class LanguageController extends Controller
{

    protected LanguageService $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index():View
    {
        $languages = Language::all();
        return view('cms.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
        return view('cms.languages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LanguageStoreRequest $request)
    {
        $result = $this->languageService->store($request);
        return redirect()->route('cms.languages.index');
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
        $result = $this->languageService->destroy($id);
        return response()->json($result);
    }


    public function activate(Request $request, $id)
    {
        $result = $this->languageService->activate($id);
        return response()->json($result);
    }

}
