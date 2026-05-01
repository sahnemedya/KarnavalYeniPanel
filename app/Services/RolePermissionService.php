<?php

namespace App\Services;

use App\Models\Blade;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RolePermissionService
{


    public function edit($id)
    {
        $status = "success";
        $message = "Role ve İzinler Bulundu";

        try {
            $role = Role::findOrFail($id);
            $permissions = Permission::orderBy("name")->get();
            LogService::add("Role Permission Service Edit", $status, $message);
            return [
                "status" => $status,
                "message" => $message,
                "role" => $role,
                "permissions" => $permissions
            ];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Role ve İzinler Bulunamadı";
            LogService::add("Role Permission Service Edit", $status, $message . " => " . $exception->getMessage());
            return [
                "status" => $status,
                "message" => $message,
            ];
        }

    }

    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Kullanıcı Yetkisi Güncellendi";

        try {
            $roleUser = RoleUser::findOrFail($id);
            $roleUser->update([
                "user_id" => $request->user_id,
                "role_id" => $request->role_id
            ]);
            LogService::add("Role User Service Update", $status, $roleUser->user->name . '  => ' . $message);
            return [
                "status" => $status,
                "message" => $message
            ];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Kullanıcı Yetkisi Güncellenemedi.';
            LogService::add("Role User Service Update", $status, $message . " => " . $exception->getMessage());
            return [
                "status" => $status,
                "message" => $message
            ];
        }
    }

    public function destroy($id)
    {
        $status = 'success';
        $message = 'Kullanıcı Yetkisi Silindi';

        try {
            $roleUser = RoleUser::findOrFail($id);
            $roleUser->delete();
            LogService::add("Role Service Destroy", $status, $roleUser->user->name . ' => ' . $message);
            return [
                "status" => $status,
                "message" => $message
            ];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Kullanıcı Yetkisi Silinemedi.';
            LogService::add("Role Service Destroy", $status, $message . " => " . $exception->getMessage());
            return [
                "status" => $status,
                "message" => $message
            ];
        }
    }

    public function toggle($role, $permission)
    {
        $status = "success";
        $message = $role . " Rolüne " . $permission . " İzni Verildi";
        $action = "bg-success";
        try {
            $rolePermission = RolePermission::where('role_id', $role)
                ->where('permission_id', $permission)
                ->first();

            if ($rolePermission) {
                // Soft delete yap
                $rolePermission->delete();
                $status = 'deleted';
                $message = $role . " Rolünün " . $permission . " İzni Kaldırıldı";
                $action = "bg-danger";

            } else {
                // Yeni kayıt oluştur
                RolePermission::create([
                    'role_id' => $role,
                    'permission_id' => $permission,
                ]);
                $action = "bg-success";
            }
            LogService::add("Role Permission Service Toggle", $status, $message);
            return ["status" => $status, "message" => $message, "action" => $action];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Rol İzin Ekleme/Çıkarma Hatası';
            $action = "bg-warning";
            LogService::add("Role Permission Service Toggle", $status, $message . " => " . $exception->getMessage());
            return [
                "status" => $status,
                "message" => $message,
                "action" => $action
            ];

        }
    }


}
