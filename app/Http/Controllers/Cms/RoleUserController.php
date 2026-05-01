<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleUserStoreRequest;
use App\Http\Requests\RoleUserUpdateRequest;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use App\Services\RoleUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleUserController extends Controller
{
    protected RoleUserService $roleUserService;

    public function __construct(RoleUserService $roleUserService)
    {
        $this->roleUserService = $roleUserService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleUser = RoleUser::all();
        return view('cms.roleUser.index', compact('roleUser'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::whereDoesntHave('roles')->get();
        $roles = Role::all();
        return view('cms.roleUser.create', compact('roles', 'users'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleUserStoreRequest $request)
    {
        $result = $this->roleUserService->store($request);
        return redirect()->route('cms.role-user.index')
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
        $roleUser = RoleUser::find($id);
        $roles = Role::all();
        return view('cms.roleUser.edit', compact('roleUser', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUserUpdateRequest $request, string $id)
    {
        $result = $this->roleUserService->update($request, $id);
        return redirect()->route('cms.role-user.index')
            ->with("status", $result["status"])
            ->with("message", $result["message"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->roleUserService->destroy($id);
        return response()->json([
            "status" => $result["status"],
            "message" => $result["message"]
        ]);
    }
}
