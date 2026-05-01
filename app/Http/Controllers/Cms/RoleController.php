<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Models\Role;
use App\Services\RolesService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected RolesService $rolesService;

    public function __construct(RolesService $rolesService)
    {
        $this->rolesService = $rolesService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('cms.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStoreRequest $request)
    {
        $result = $this->rolesService->store($request);
        return redirect()->route('cms.roles.index')
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
        $result = $this->rolesService->edit($id);
        if ($result["status"] == "success") {
            return view('cms.roles.edit', [
                'role' => $result["role"]
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
    public function update(RoleStoreRequest $request, string $id)
    {
        $result = $this->rolesService->update($request, $id);
        return redirect()->route('cms.roles.index')
            ->with("status", $result["status"])
            ->with("message", $result["message"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->rolesService->destroy($id);
        return response()->json([
            "status" => $result["status"],
            "message" => $result["message"]
        ]);
    }
}
