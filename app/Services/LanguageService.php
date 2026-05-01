<?php

namespace App\Services;

use App\Http\Requests\LanguageStoreRequest;
use App\Models\Language;

class LanguageService
{

    public function store(LanguageStoreRequest $request)
    {
        $status = 'success';
        $message = $request->name . " Kaydedildi";
        try {
            $language = Language::create([
                "name" => $request->get('name'),
                "code" => $request->get('code'),
            ]);

        } catch (\Throwable $exception) {
            $status = 'error';
            $message = "Kaydedilemedi => " . $exception->getMessage();

        }
        LogService::add("Language Service Store", $status, $message);
        return [
            'status' => $status,
            'message' => $message
        ];

    }

    public function activate($id)
    {
        $status = 'success';
        $message = "Aktif Edildi";
        try {
            $language = Language::findOrFail($id);
            if ($language->active == 1) {
                $update = $language->update(['active' => 0]);
                if (!$update) {
                    $status = "warning";
                    $message = $language->name . " Deaktif Edilemedi";
                } else {
                    $message = $language->name . " Deaktif Edildi";
                }
            } else {
                $update = $language->update(['active' => 1]);
                if (!$update) {
                    $status = "warning";
                    $message = $language->name . " Aktif Edilemedi.";
                } else {
                    $message = $language->name . " " . $message;
                }
            }

        } catch (\Throwable $exception) {
            $status = 'error';
            $message = "Kaydedilemedi.";
//            Hata varsa log kaydına hata mesajını yazdır
            LogService::add("Language Service Activate", $status, $message . ' => ' . $exception->getMessage());
        }
        //Hata yoksa log kaydını olduğu gibi al
        if ($status !== "error") {
            LogService::add("Language Service Activate", $status, $message);
        }
        return [
            'status' => $status,
            'message' => $message
        ];

    }

    public function destroy($id)
    {
        $status = 'success';
        $message = "Silindi.";
        try {
            $language = Language::findOrFail($id);
            if ($language->delete()) {
                $message = $language->name . " Silindi.";
            } else {
                $status = 'warning';
                $message = $language->name . " Silinemedi.";
            }

            LogService::add("Language Service Destroy", $status,  $message);

        } catch (\Throwable $exception) {
            $status = 'error';
            $message = 'Silinemedi ';
            LogService::add("Language Service Destroy", $status, $message . ' => ' . $exception->getMessage());
        }

        return ['status' => $status, 'message' => $message];


    }


}
