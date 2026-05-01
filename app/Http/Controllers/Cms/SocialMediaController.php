<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\SocialMediaService;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    protected SocialMediaService $socialMediaService;

    public function __construct(SocialMediaService $socialMediaService)
    {
        $this->socialMediaService = $socialMediaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($contactId)
    {
        $result = $this->socialMediaService->create($contactId);
        if ($result["status"] == "success") {
            return view('cms.socialMedia.create', compact('contactId'));
        } else {
            return redirect()->route('cms.contacts.index')
                ->with("status", $result["status"])
                ->with("message", $result["message"]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $contactId)
    {
        $result = $this->socialMediaService->store($request, $contactId);
        return redirect()->route('cms.contacts.index')
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
    public function edit($id)
    {
        $result = $this->socialMediaService->edit($id);
        if ($result["status"] == "success") {
            return view('cms.socialMedia.edit', [
                'social' => $result["socialMedia"]
            ]);
        } else {
            return redirect()->route('cms.contacts.index')
                ->with("status", $result["status"])
                ->with("message", $result["message"]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = $this->socialMediaService->update($request, $id);
        return redirect()->route('cms.contacts.index')
            ->with('status', $result['status'])
            ->with('message', $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->socialMediaService->destroy($id);
        return response()->json([
           "status" => $result["status"],
           "message" => $result["message"]
        ]);
    }
}
