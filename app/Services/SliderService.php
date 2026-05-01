<?php

namespace App\Services;

use App\Models\ApiKeys;
use App\Models\Blade;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SliderService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function store(Request $request)
    {
        $status = "success";
        $message = "Slayt Kaydedildi";

        try {
            $slug = Str::slug($request->title, "-");
            $image = NULL;
            $mobileImage = NULL;

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $image = $slug . "." . $extension;
                $this->commonService->uploadFile(config('constants.slide_path'), $imageFile, $image);
            }
            if ($request->hasFile('mobile_image')) {
                $imageFile = $request->file('mobile_image');
                $extension = $imageFile->guessExtension();
                $mobileImage = $slug . "-mobile." . $extension;
                $this->commonService->uploadFile(config('constants.slide_path'), $imageFile, $mobileImage);
            }

            $slide = Slider::create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $image,
                'mobile_image' => $mobileImage,
                'url' => $request->url,
                'hit' => $request->hit,
                'lang_id' => $request->lang_id,
            ]);

            LogService::add("Slider Service Store", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Slayt Kaydedilemedi";
            LogService::add("Slider Service Store", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function edit($id)
    {
        try {
            $slider = Slider::findOrFail($id);
            return ["status" => "success", "slider" => $slider];
        } catch (\Throwable $exception) {
            return ["status" => "error", "message" => "Slayt Bulunamadı"];
        }
    }

    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Slayt Güncellendi";
        try {

            $slide = Slider::findOrFail($id);
            $oldTitle = $slide->title;
            $slug = Str::slug($request->title, "-");


            if($request->removeImage){
                if($slide->image) {
                    $this->commonService->deleteFile(config('constants.slide_path'), $slide->image);
                }
                $slide->update(['image' => NULL]);
            }
            if($request->removeMobileImage){
                if($slide->mobile_image){
                    $this->commonService->deleteFile(config('constants.slide_path'), $slide->mobile_image);
                }
            }

            if($request->hasFile('image')){
                if($slide->image){
                    $this->commonService->deleteFile(config('constants.slide_path'), $slide->image);
                }

                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $image = $slug . "." . $extension;
                $this->commonService->uploadFile(config('constants.slide_path'), $imageFile, $image);
                $slide->update(["image" => $image]);
            }

            if($request->hasFile('mobile_image')){
                if($slide->mobile_image){
                    $this->commonService->deleteFile(config('constants.slide_path'), $slide->mobile_image);
                }

                $imageFile = $request->file('mobile_image');
                $extension = $imageFile->guessExtension();
                $mobileImage = $slug . "-mobile." . $extension;

                // Buranın 'slide_path' olduğundan emin olun (önceki düzeltme)
                $this->commonService->uploadFile(config('constants.slide_path'), $imageFile, $mobileImage);

                // 2. Düzeltme: $image değil, $mobileImage değişkenini kaydet
                $slide->update(["mobile_image" => $mobileImage]);
            }

            if ($oldTitle != $request->title) {
                $slug = Str::slug($request->title, "-");
                if ($slide->image) {
                    $extension = pathinfo($slide->image, PATHINFO_EXTENSION);
                    $image = $slug . "." . $extension;
                    $this->commonService->renameFile(config('constants.slide_path'), $slide->image, $image);
                    $slide->update(["image" => $image]);
                }

                if ($slide->mobile_image) {
                    $extension = pathinfo($slide->mobile_image, PATHINFO_EXTENSION);
                    $image = $slug . "-mobile." . $extension;
                    $this->commonService->renameFile(config('constants.slide_path'), $slide->mobile_image, $image);
                    $slide->update(["mobile_image" => $image]);
                }
            }



            $slide->update([
                'title' => $request->title,
                'description' => $request->description,
                'url' => $request->url,
                'hit' => $request->hit,
                'lang_id' => $request->lang_id
            ]);

            LogService::add("Slider Service Update", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Slayt Güncellenemedi";
            LogService::add("Slider Service Update", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroy($id)
    {
        $status = "success";
        $message = "Slayt Silindi";
        try {
            $slide = Slider::findOrFail($id);
            $slide->delete();
            $deleteSlide = $this->commonService->deleteFile(config("constants.slide_path"), $slide->image);
            $deleteSlide = $this->commonService->deleteFile(config("constants.slide_path"), $slide->mobile_image);
            LogService::add("Slider Service Destroy", $status, $slide->title . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Slayt Silinemedi";
            LogService::add("Slider Service Destroy", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function publish($id)
    {
        $status = "success";
        $message = "Slayt Yayınlandı";
        try {
            $slide = Slider::findOrFail($id);
            if ($slide->published == 1) {
                $slide->update(["published" => 0]);
                $message = "Slayt Yayından Kaldırıldı.";
            } else {
                $slide->update(["published" => 1]);
                $message = "Slayt Yayınlandı";
            }
            LogService::add("Slider Service Publish", $status, $slide->title . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Slayt Yayın İşlemi Başarısız";
            LogService::add("Slider Service Publish", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

}
