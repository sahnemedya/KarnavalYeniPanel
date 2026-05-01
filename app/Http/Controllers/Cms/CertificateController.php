<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Language;
use App\Services\CertificateService;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    protected CertificateService $certificateService;
    public function __construct(CertificateService $certificateService){
        $this->certificateService = $certificateService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificates = Certificate::all();
        return view('cms.certificate.index', compact('certificates'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = Language::all();
        return view('cms.certificate.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->certificateService->store($request);
        return redirect()->route('cms.certificate.index')
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
        $result = $this->certificateService->destroy($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    public function publish($id)
    {
        $result = $this->certificateService->publish($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }


}
