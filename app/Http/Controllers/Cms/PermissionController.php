<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('cms.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->permissionService->store($request);
        return redirect()->route('cms.permission.index')
            ->with("status", $result["status"])
            ->with("message", $result["message"]);
        //
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
        $result = $this->permissionService->edit($id);
        if ($result["status"] == "success") {
            return view('cms.permissions.edit', [
                'permission' => $result["permission"]
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
        $result = $this->permissionService->update($request, $id);
        return redirect()->route('cms.permission.index')
            ->with("status", $result["status"])
            ->with("message", $result["message"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->permissionService->destroy($id);
        return response()->json([
            "status" => $result["status"],
            "message" => $result["message"]
        ]);
    }
}
