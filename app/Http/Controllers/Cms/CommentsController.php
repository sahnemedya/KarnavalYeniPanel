<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use App\Models\Language;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $comments = Comments::all();
        $languages = Language::all();
        return view('cms.comments.index', compact('languages', 'comments'));
    }

    public function create()
    {
        $languages = Language::all();
        return view('cms.comments.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $result = $this->commentService->store($request);
        return redirect()->route('cms.comments.index')
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
    public function edit(string $id)
    {
        $languages = Language::all();
        $comments = Comments::findOrFail($id);
        return view('cms.comments.edit', compact('comments', 'languages'));
    }

    public function update(Request $request, string $id)
    {
        $result = $this->commentService->update($request, $id);
        return redirect()->route('cms.comments.index')
            ->with("status", $result['status'])->with("message", $result['message']);
    }

    public function destroy(string $id): JsonResponse
    {
        $result = $this->commentService->destroy($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    public function deleted()
    {
        $languages = Language::all();
        $comments = Comments::onlyTrashed()->get();
        return view('cms.comments.deleted', compact('languages', 'comments'));
    }

    public function restore(string $id): JsonResponse
    {
        $result = $this->commentService->restore($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    public function forceDelete(string $id)
    {
        $result = $this->commentService->forceDelete($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    public function publish($id)
    {
        $result = $this->commentService->publish($id);
        return response()->json($result);
    }
}
