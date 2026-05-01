<?php

namespace App\Services;

use App\Models\Blade;
use App\Models\Contacts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContactsService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
        //$this->commonService-> olarak kullanılacak
    }

    public function store(Request $request)
    {
        $status = 'success';
        $message = 'İletişim Bilgisi Kaydedildi';

        try {
            $contact = Contacts::create([
                "name" => $request->name,
                "email" => $request->email,
                "email2" => $request->email2,
                "phone" => $request->phone,
                "phone2" => $request->phone2,
                "address" => $request->address,
                "country" => $request->country,
                "city" => $request->city,
                "state" => $request->state,
                "person" => $request->person,
                "map" => $request->map,
                "hit" => $request->hit,
            ]);
            LogService::add("Contacts Service Store", $status, $contact->name . ' ' . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = "İletişim Bilgisi Kaydedilemedi";
            LogService::add("Contacts Service Store", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function update(Request $request,$id)
    {
        $status = 'success';
        $message = 'İletişim Bilgisi Güncellendi';

        try {
            $contact=Contacts::findOrFail($id);
            $contact->update([
                "name" => $request->name,
                "email" => $request->email,
                "email2" => $request->email2,
                "phone" => $request->phone,
                "phone2" => $request->phone2,
                "address" => $request->address,
                "country" => $request->country,
                "city" => $request->city,
                "state" => $request->state,
                "person" => $request->person,
                "map" => $request->map,
                "hit" => $request->hit,
            ]);
            LogService::add("Contacts Service Update", $status, $contact->name . ' ' . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = "İletişim Bilgisi Güncellenemedi";
            LogService::add("Contacts Service Update", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroy($id)
    {
        $status = 'success';
        $message = 'İletişim Bilgisi Silindi';

        try {
            $contact = Contacts::findOrFail($id);
            $contact->delete();
            LogService::add("Contacts Service Destroy", $status, $contact->name . ' ' . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'İletişim Bilgisi Silinemedi';
            LogService::add('Contacts Service Destroy', $status, $message . " => " . $exception->getMessage());

            return ['status' => $status, 'message' => $message];
        }

    }

    public function restore($id)
    {
        $status = 'success';
        $message = 'İletişim Bilgisi Geri Yüklendi.';

        try {
            $contact = Contacts::onlyTrashed()->findOrFail($id);
            $contact->restore();
            LogService::add("Contacts Service Restore", $status, $contact->name . ' ' . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'İletişim Bilgisi Geri Yüklenemedi';
            LogService::add("Contacts Service Restore", $status, $message . " => " . $exception->getMessage());
            return ['status' => $status, 'message' => $message];

        }
    }

    public function forceDelete($id)
    {
        $status = 'success';
        $message = 'İletişim Bilgisi Silindi.';

        try {
            $contact = Contacts::onlyTrashed()->findOrFail($id);
            $contact->forceDelete();
            LogService::add("Contacts Service ForceDelete", $status, $contact->name . ' ' . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'İletişim Bilgisi Silinemedi';
            LogService::add("Contacts Service ForceDelete", $status, $message . ' => ' . $exception->getMessage());
            return ['status' => $status, 'message' => $message];
        }
    }


}
