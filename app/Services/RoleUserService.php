<?php

namespace App\Services;

use App\Models\Blade;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RoleUserService
{

    public function store(Request $request)
    {
        $status = 'success';
        $message = 'Kullanıcıya Yetki Atandı.';
        try {

            $roleUser = RoleUser::create([
                "user_id" => $request->user_id,
                "role_id" => $request->role_id
            ]);

            LogService::add("Role User Service Store", $status, $roleUser->user->name . ' => ' . $message);

            return [
                'status' => $status,
                'message' => $message
            ];

        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Kullanıcıya Yetki Atanamadı.';
            LogService::add('Role User Service Store', $status, $message . '=>' . $exception->getMessage());
            return [
                'status' => $status,
                'message' => $message
            ];
        }
    }

    public function edit($id)
    {
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


}
