<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\BladeStoreRequest;
use App\Models\Blade;
use App\Services\BladeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class BladeController extends Controller
{

    protected BladeService $bladeService;

    public function __construct(BladeService $bladeService)
    {
        $this->bladeService = $bladeService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        $blades =  Blade::all();
        return view('cms.blades.index', compact('blades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('cms.blades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->bladeService->store($request);
        if ($result) {
            return redirect()->route('cms.blades.index')->with('status', $result['status'])->with('message', $result['message']);
        } else {
            return redirect()->back();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $result = $this->bladeService->destroy($id);
        return response()->json($result);
    }
}
