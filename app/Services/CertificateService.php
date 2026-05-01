<?php

namespace App\Services;

use App\Models\ApiKeys;
use App\Models\Blade;
use App\Models\Certificate;
use App\Models\Popup;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function store(Request $request)
    {
        $status = "success";
        $message = "Sertifika Kaydedildi";

        try {
            $slug = Str::slug($request->name, "-") . "-" . Str::lower(Str::random(5));
            $image = NULL;
            $file = NULL;

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $image = $slug . "." . $extension;
                $this->commonService->uploadFile(config('constants.certificates_path'), $imageFile, $image);
            }
            if ($request->hasFile('file')) {
                $documentFile = $request->file('file');
                $extension = $documentFile->guessExtension();
                $file = $slug . "." . $extension;
                $this->commonService->uploadFile(config('constants.cetificates_files_path'), $documentFile, $file);
            }

            $certificate = Certificate::create([
                'name' => $request->name,
                'image' => $image,
                'file' => $file,
                'type' => $request->type,
                'hit' => $request->hit,
                'lang_id' => $request->lang_id,
            ]);

            LogService::add("Certificates Service Store", $status, $certificate->name . " " . $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Sertifika Kaydedilemedi";
            LogService::add("Certificates Service Store", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroy($id)
    {
        $status = "success";
        $message = "Sertifika Silindi";
        try {
            $certificate = Certificate::findOrFail($id);
            $certificate->delete();
            $deleteCertificate = $this->commonService->deleteFile(config("constants.certificates_path"), $certificate->image);
            $deleteCertificate = $this->commonService->deleteFile(config("constants.cetificates_files_path"), $certificate->file);
            LogService::add("Certificate Service Destroy", $status, $certificate->name . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Sertifika Silinemedi";
            LogService::add("Certificate Service Destroy", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function publish($id)
    {
        $status = "success";
        $message = "Sertifika Yayınlandı";
        try {
            $certificate = Certificate::findOrFail($id);
            if ($certificate->published == 1) {
                $certificate->update(["published" => 0]);
                $message = "Sertifika Yayından Kaldırıldı.";
            } else {
                $certificate->update(["published" => 1]);
                $message = "Sertifika Yayınlandı";
            }
            LogService::add("Certificate Service Publish", $status, $certificate->name . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Sertifika Yayın İşlemi Başarısız";
            LogService::add("Certificate Service Publish", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

}
