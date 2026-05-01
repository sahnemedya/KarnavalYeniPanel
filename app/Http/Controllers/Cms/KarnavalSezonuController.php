<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\KarnavalSezonu;
use Illuminate\Http\Request;
use App\Http\Requests\KarnavalSezonuRequest; // Artık sadece tek bir Request dosyamız var
use App\Services\KarnavalSezonuService;
use Illuminate\Http\JsonResponse;

class KarnavalSezonuController extends Controller
{
    protected $karnavalSezonuService;

    function __construct(KarnavalSezonuService $karnavalSezonuService)
    {
        $this->karnavalSezonuService = $karnavalSezonuService;
    }

    public function index()
    {
        $karnavalSezonlari  = KarnavalSezonu::all();
        return view('cms.karnaval-sezonu.index', compact('karnavalSezonlari'));
    }

    public function create()
    {
        return view('cms.karnaval-sezonu.create');
    }

    /**
     * Ekleme işlemi için KarnavalSezonuRequest kullanıyoruz.
     */
    public function store(KarnavalSezonuRequest $request)
    {
        $result = $this->karnavalSezonuService->store($request);
        return redirect()->route('cms.karnaval-sezonu.index')->with("status", $result['status'])->with("message", $result['message']);
    }

    public function show(KarnavalSezonu $karnavalSezonlari)
    {
        return view('cms.karnaval-sezonu.show', compact('karnavalSezonlari'));
    }

    public function edit(string $id)
    {
        $karnavalSezonlari = KarnavalSezonu::findOrFail($id);
        return view('cms.karnaval-sezonu.edit', compact('karnavalSezonlari'));
    }

    /**
     * Güncelleme işlemi için de AYNI KarnavalSezonuRequest sınıfını kullanıyoruz.
     */
    public function update(KarnavalSezonuRequest $request, string $id)
    {
        $result = $this->karnavalSezonuService->update($request, $id, $request->validated());

        return redirect()->route('cms.karnaval-sezonu.index')
            ->with("status", $result['status'])->with("message", $result['message']);
    }


    public function destroy(string $id): JsonResponse
    {
        $result = $this->karnavalSezonuService->destroy($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
        ]);
    }
    /**
     * Silinen kayıtları listeler
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function deleted()
    {
        $karnavalSezonlari = KarnavalSezonu::onlyTrashed()->get();
        return view('cms.karnaval-sezonu.deleted', compact('karnavalSezonlari'));
    }
    public function restore(string $id): JsonResponse
    {
        $result = $this->karnavalSezonuService->restore($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }
    public function forceDelete(string $id)
    {
        $result = $this->karnavalSezonuService->forceDelete($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    public function activate($id)
    {
        $result = $this->karnavalSezonuService->activate($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }
}
