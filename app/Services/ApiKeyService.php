<?php

namespace App\Services;

use App\Models\ApiKeys;
use App\Models\Blade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApiKeyService
{
    protected CommonService $commonService;

    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Api Keyleri Güncellendi";
        try {
            $apiKey = ApiKeys::findOrFail($id);
            $apiKey->update([
                'youtube_channel_id' => $request->youtube_channel_id,
                'recaptcha_site_key' => $request->recaptcha_site_key,
                'recaptcha_secret_key' => $request->recaptcha_secret_key,
            ]);
            LogService::add("ApiKey Service Update", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Api Keyleri Güncellenemedi";
            LogService::add("ApiKey Service Update", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

}
