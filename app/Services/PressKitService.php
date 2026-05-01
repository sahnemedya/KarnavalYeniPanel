<?php

namespace App\Services;

use App\Models\ApiKeys;
use App\Models\Blade;
use App\Models\Certificate;
use App\Models\Popup;
use App\Models\PressKit;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PressKitService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function store(Request $request)
    {
        $status = "success";
        $message = "Basın Kiti Kaydedildi";

        try {
            $slug = Str::slug($request->name, "-") . "-" . Str::lower(Str::random(5));
            $image = NULL;
            $file = NULL;

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $image = $slug . "." . $extension;
                $this->commonService->uploadFile(config('constants.press_kit_path'), $imageFile, $image);
            }
            if ($request->hasFile('file')) {
                $documentFile = $request->file('file');
                $extension = $documentFile->guessExtension();
                $file = $slug . "." . $extension;
                $this->commonService->uploadFile(config('constants.press_kit_files_path'), $documentFile, $file);
            }

            $pressKit = PressKit::create([
                'name' => $request->name,
                'image' => $image,
                'file' => $file,
                'lang_id' => $request->lang_id,
            ]);

            LogService::add("Press Kit Service Store", $status, $pressKit->name . " " . $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Basın Kiti Kaydedilemedi";
            LogService::add("Press Kit Service Store", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroy($id)
    {
        $status = "success";
        $message = "Basın Kiti Silindi";
        try {
            $pressKit = PressKit::findOrFail($id);
            $pressKit->delete();
            $deletePressKit = $this->commonService->deleteFile(config("constants.press_kit_path"), $pressKit->image);
            $deletePressKit = $this->commonService->deleteFile(config("constants.press_kit_files_path"), $pressKit->file);
            LogService::add("Press Kit Service Destroy", $status, $pressKit->name . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Basın Kiti Silinemedi";
            LogService::add("Press Kit Service Destroy", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }


}
