<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactStoreRequest;
use App\Models\Contacts;
use App\Services\ContactsService;
use Illuminate\Http\Request;

class ContactsController extends Controller
{

    protected ContactsService $contactsService;

    public function __construct(ContactsService $contactsService)
    {
        $this->contactsService = $contactsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contacts::all();
        return view('cms.contact.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.contact.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactStoreRequest $request)
    {
        $result = $this->contactsService->store($request);
        return redirect()->route('cms.contacts.index')
            ->with("status", $result["status"])
            ->with("message", $result["message"]);

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
        $contact = Contacts::find($id);
        return view('cms.contact.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactStoreRequest $request, string $id)
    {
        $result = $this->contactsService->update($request, $id);
        return redirect()->route('cms.contacts.index')
            ->with("status", $result["status"])
            ->with("message", $result["message"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->contactsService->destroy($id);
        return response()->json(["status" => $result["status"], "message" => $result["message"]]);

    }

    public function deleted()
    {
        $contacts = Contacts::onlyTrashed()->get();
        return view('cms.contact.deleted', compact('contacts'));
    }

    public function restore(Request $request, $id)
    {
        $result = $this->contactsService->restore($id);
        return response()->json(["status" => $result["status"], "message" => $result["message"]]);
    }

    public function forceDelete(Request $request, $id)
    {
        $result = $this->contactsService->forceDelete($id);
        return response()->json(["status" => $result["status"], "message" => $result["message"]]);
    }

}
