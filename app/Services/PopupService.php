<?php

namespace App\Services;

use App\Models\ApiKeys;
use App\Models\Blade;
use App\Models\Popup;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PopupService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function store(Request $request)
    {
        $status = "success";
        $message = "Pop-Up Kaydedildi";

        try {
            $slug = Str::slug($request->title, "-");
            $image = NULL;
            $mobileImage = NULL;

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $image = $slug . "." . $extension;
                $this->commonService->uploadFile(config('constants.popup_path'), $imageFile, $image);
            }
            if ($request->hasFile('mobile_image')) {
                $imageFile = $request->file('mobile_image');
                $extension = $imageFile->guessExtension();
                $mobileImage = $slug . "-mobile." . $extension;
                $this->commonService->uploadFile(config('constants.popup_path'), $imageFile, $mobileImage);
            }

            $popup = Popup::create([
                'title' => $request->title,
                'image' => $image,
                'mobile_image' => $mobileImage,
                'url' => $request->url,
                'lang_id' => $request->lang_id,
            ]);

            LogService::add("Pop-Up Service Store", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Pop-Up Kaydedilemedi";
            LogService::add("Pop-Up Service Store", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroy($id)
    {
        $status = "success";
        $message = "Pop-Up Silindi";
        try {
            $popup = Popup::findOrFail($id);
            $popup->delete();
            $deletePopup = $this->commonService->deleteFile(config("constants.popup_path"), $popup->image);
            $deletePopup = $this->commonService->deleteFile(config("constants.popup_path"), $popup->mobile_image);
            LogService::add("Pop-Up Service Destroy", $status, $popup->title . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Pop-Up Silinemedi";
            LogService::add("Pop-Up Service Destroy", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function publish($id)
    {
        $status = "success";
        $message = "Pop-Up Yayınlandı";
        try {
            $popup = Popup::findOrFail($id);
            if ($popup->published == 1) {
                $popup->update(["published" => 0]);
                $message = "Pop-Up Yayından Kaldırıldı.";
            } else {
                $popup->update(["published" => 1]);
                $message = "Pop-Up Yayınlandı";
            }
            LogService::add("Pop-Up Service Publish", $status, $popup->title . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Pop-Up Yayın İşlemi Başarısız";
            LogService::add("Pop-Up Service Publish", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

}
