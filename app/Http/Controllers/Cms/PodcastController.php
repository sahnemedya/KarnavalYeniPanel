<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use App\Services\PodcastServices;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    protected PodcastServices $podcastServices;

    public function __construct(PodcastServices $podcastServices)
    {
        $this->podcastServices = $podcastServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Service'den podcast verilerini çek
        $data = $this->podcastServices->index();

        return view('cms.podcasts.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $selectedPage = null;

        if ($request->has('page_id')) {
            $selectedPage = Page::find($request->get('page_id'));
        }

        $languages = Language::all();

        return view(
            'cms.podcasts.create',
            compact('languages', 'selectedPage')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->podcastServices->store($request);

        return redirect()
            ->route('cms.podcast.index')
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
        $result = $this->podcastServices->destroy($id);

        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }
}
