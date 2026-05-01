<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Doctor;
use App\Models\Page;
use App\Services\DoctorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    protected DoctorService $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::all();
        return view('cms.doctors.index', compact("doctors"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $medicalUnit = Category::where("is_medical_unit", 1)->first();
        return view('cms.doctors.create', compact('medicalUnit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->doctorService->store($request);

        return redirect()
            ->route('cms.doctors.index')
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
        $result = $this->doctorService->edit($id);
        if ($result["status"] == "success") {
            return view('cms.doctors.edit', [
                "doctor" => $result["doctor"],
                "medicalUnit" => $result["medicalUnit"],
            ]);
        } else {
            return view('cms.doctors.index', [
                "status" => $result["status"],
                "message" => $result["message"]
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = $this->doctorService->update($request, $id);

        return redirect()
            ->route('cms.doctors.index')
            ->with('status', $result['status'])
            ->with('message', $result['message']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->doctorService->destroy($id);
        return response()->json([
            "status" => $result["status"],
            "message" => $result["message"]
        ]);
    }

    public function deleted()
    {
        $result = $this->doctorService->deleted();
        return view('cms.doctors.deleted', [
            "doctors" => $result["doctors"]
        ]);
    }

    public function forceDelete(string $id)
    {
        $result = $this->doctorService->forceDelete($id);
        return response()->json($result);
    }

    public function restore($id)
    {
        $result = $this->doctorService->restore($id);

        return response()->json($result);
    }
    /**
     * Sayfanın Yayınlanmasını sağlamak için kullanabilirsiniz.
     * @param string $id
     * @return JsonResponse
     */
    public function publishPage(string $id): JsonResponse
    {
        $result = $this->doctorService->publishPage($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
        ]);
    }
    public function activate(Request $request, string $id)
    {
        $result = $this->doctorService->activate($request, $id);
        return response()->json($result);
    }

}
