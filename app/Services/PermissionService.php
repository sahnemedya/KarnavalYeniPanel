<?php

namespace App\Services;

use App\Models\Blade;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PermissionService
{

    public function store(Request $request)
    {
        $status = 'success';
        $message = 'Yetki İzni Kaydedildi';
        try {

            $permission = Permission::create([
                "name" => $request->name,
                "label" => Str::slug($request->name, "-")
            ]);

            LogService::add("Permission Service Store", $status, $permission->name . ' => ' . $message);

            return [
                'status' => $status,
                'message' => $message
            ];

        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Yetki İzni Kaydedilemedi.';
            LogService::add('Permission Service Store', $status, $message . '=>' . $exception->getMessage());
            return [
                'status' => $status,
                'message' => $message
            ];
        }
    }
    public function edit($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            return [
                "status" => "success",
                "message" => "İzni Bulundu.",
                "permission" => $permission];
        } catch (\Throwable $exception) {
            LogService::add("Permission Service Edit", "error", "Yetki İzni Bulunamadı: => " . $exception->getMessage());
            return ["status" => "error", "message" => "Role Bulunamadı."];
        }
    }
    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "İzni Güncellendi";

        try {
            $permission = Permission::findOrFail($id);
            $permission->update([
                "name" => $request->name,
                "label" => Str::slug($request->name, "-")
            ]);
            LogService::add("Permission Service Update", $status, $permission->name . ' => ' . $message);
            return [
                "status" => $status,
                "message" => $message
            ];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'İzni Güncellenemedi.';
            LogService::add("Permission Service Update", $status, $message . " => " . $exception->getMessage());
            return [
                "status" => $status,
                "message" => $message
            ];
        }
    }
    public function destroy($id)
    {
        $status = 'success';
        $message = 'İzni Silindi';

        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            LogService::add("Permission Service Destroy", $status, $permission->name . ' => ' . $message);
            return [
                "status" => $status,
                "message" => $message
            ];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'İzni Bilgisi Silinemedi.';
            LogService::add("Permission Service Destroy", $status, $message . " => " . $exception->getMessage());
            return [
                "status" => $status,
                "message" => $message
            ];
        }
    }


}
