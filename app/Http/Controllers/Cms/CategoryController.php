<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Blade;
use App\Models\Category;
use App\Models\Language;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class CategoryController extends Controller
{

    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::all();
        return view('cms.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::all();
        $languages = Language::all();
        return view('cms.category.create', compact('categories', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $result = $this->categoryService->store($request);
        return redirect()->route('cms.category.index')->with('status', $result['status'])->with('message', $result['message']);
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
        $category = Category::findOrFail($id);
        $categories = Category::all();
        $languages = Language::all();
        return view('cms.category.edit', compact('categories', 'languages', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, string $id)
    {
        $result = $this->categoryService->update($request, $id);
        return redirect()->route('cms.category.index')->with('status', $result['status'])->with('message', $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->categoryService->destroy($id);
        return response()->json($result);
    }

    public function deleted()
    {
        $categories = Category::onlyTrashed()->get();
        return view('cms.category.deleted', compact('categories'));
    }

    public function forceDelete(string $id)
    {
        $result = $this->categoryService->forceDelete($id);
        return response()->json($result);
    }

    public function restore($id)
    {
        $result = $this->categoryService->restore($id);

        return response()->json($result);
    }

    public function activate(Request $request, string $id)
    {
        $result = $this->categoryService->activate($request, $id);
        return response()->json($result);
    }

}
