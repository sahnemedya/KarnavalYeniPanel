<?php

namespace App\Services;

use App\Models\Blade;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RolesService
{

    public function store(Request $request)
    {
        $status = 'success';
        $message = 'Kullanıcı Rolü Kaydedildi';
        try {

            $role = Role::create([
                "name" => $request->name,
                "label" => Str::slug($request->name, "-")
            ]);

            LogService::add("Role Service Store", $status, $role->name . ' => ' . $message);

            return [
                'status' => $status,
                'message' => $message
            ];

        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Kullanıcı Rolü Kaydedilemedi.';
            LogService::add('Rol Service Store', $status, $message . '=>' . $exception->getMessage());
            return [
                'status' => $status,
                'message' => $message
            ];
        }
    }
    public function edit($id)
    {
        try {
            $role = Role::findOrFail($id);
            return [
                "status" => "success",
                "message" => "Role Bilgileri Bulundu.",
                "role" => $role];
        } catch (\Throwable $exception) {
            LogService::add("Role Service Edit", "error", "Role Bilgileri Bulunamadı: => " . $exception->getMessage());
            return ["status" => "error", "message" => "Role Bulunamadı."];
        }
    }
    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Role Bilgisi Güncellendi";

        try {
            $role = Role::findOrFail($id);
            $role->update([
                "name" => $request->name,
                "label" => Str::slug($request->name, "-")
            ]);
            LogService::add("Role Service Update", $status, $role->name . ' => ' . $message);
            return [
                "status" => $status,
                "message" => $message
            ];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Role Bilgisi Güncellenemedi.';
            LogService::add("Role Service Update", $status, $message . " => " . $exception->getMessage());
            return [
                "status" => $status,
                "message" => $message
            ];
        }
    }
    public function destroy($id)
    {
        $status = 'success';
        $message = 'Role Silindi';

        try {
            $role = Role::findOrFail($id);
            $role->delete();
            LogService::add("Role Service Destroy", $status, $role->name . ' => ' . $message);
            return [
                "status" => $status,
                "message" => $message
            ];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Role Bilgisi Silinemedi.';
            LogService::add("Role Service Destroy", $status, $message . " => " . $exception->getMessage());
            return [
                "status" => $status,
                "message" => $message
            ];
        }
    }


}
