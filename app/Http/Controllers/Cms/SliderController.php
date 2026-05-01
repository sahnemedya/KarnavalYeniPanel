<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderStoreRequest;
use App\Models\Language;
use App\Models\Slider;
use App\Services\SliderService;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    protected SliderService $sliderService;

    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::orderBy("hit", "asc")->orderBy("title", "asc")->get();
        return view('cms.slider.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = Language::all();
        return view('cms.slider.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SliderStoreRequest $request)
    {
        $result = $this->sliderService->store($request);
        return redirect()->route('cms.slider.index')
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
        $result = $this->sliderService->edit($id);
        if ($result['status'] == "success") {
            $slider = $result["slider"];
            $languages = Language::all();
            return view('cms.slider.edit', compact("slider","languages"));
        } else {
            return redirect()->route('cms.slider.index')
                ->with("status", $result['status'])
                ->with("message", $result['message']);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = $this->sliderService->update($request, $id);
        return redirect()->route('cms.slider.index')
            ->with("status", $result['status'])
            ->with("message", $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->sliderService->destroy($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    public function publish($id)
    {
        $result = $this->sliderService->publish($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

}
