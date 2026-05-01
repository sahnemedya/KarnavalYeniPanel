<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Services\RolePermissionService;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{

    protected RolePermissionService $rolePermissionService;

    public function __construct(RolePermissionService $rolePermissionService)
    {
        $this->rolePermissionService = $rolePermissionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('cms.rolePermission.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        $result = $this->rolePermissionService->edit($id);
        if ($result["status"] == "success") {
            return view('cms.rolePermission.edit', [
                'role' => $result["role"],
                'permissions' => $result["permissions"]
            ]);
        } else {
            return redirect()->route('cms.roles.index')
                ->with("status", $result["status"])
                ->with("message", $result["message"]);
        }

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
        //
    }

    public function toggle(Request $request, $role, $permission)
    {
        $result = $this->rolePermissionService->toggle($role, $permission);
        return response()->json([
            "status" => $result["status"],
            "message" => $result["message"],
            "action" => $result["action"]
        ]);
    }
}
