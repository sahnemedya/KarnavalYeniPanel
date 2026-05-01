<?php

namespace App\Services;


use App\Models\References;
use App\Models\ReferenceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferenceService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function store(Request $request)
    {
        $status = "success";
        $message = "Referans Kaydedildi";

        try {
            $slug = Str::slug($request->name, "-");
            $image = NULL;

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $image = $slug . "." . $extension;
                $this->commonService->uploadFile(config('constants.references_path'), $imageFile, $image);
            }

            $reference = References::create([
                'sezon_id'=>$request->sezon_id,
                'type_id' => $request->type_id,
                'name' => $request->name,
                'image' => $image,
                'hit' => $request->hit,
                'url' => $request->url,
                'lang_id' => $request->lang_id,
            ]);

            LogService::add("Reference Service Store", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Referans Kaydedilemedi";
            LogService::add("Reference Service Store", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroy($id)
    {
        $status = "success";
        $message = "Refeans Gecici Olarak Silindi";
        try {
            $reference = References::findOrFail($id);
            $reference->delete();
            LogService::add("Reference Service Destroy", $status, $reference->name . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Referans Gecici Olarak Silinemedi";
            LogService::add("Reference Service Destroy", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }
    public function restore($id)
    {
        $status = "success";
        $message = 'Referans Geri Yüklendi';
        try {
            $reference = References::onlyTrashed()->findOrFail($id);
            $reference->restore();
            $message = $reference->title . ' ' . $message;
            LogService::add("Referans Service Restore", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Referans Geri Yüklenemedi";
            LogService::add("Referans Service Restore ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }

    }

    public function forceDelete($id)
    {
        $status = "success";
        $message = 'Referans Kalıcı Olarak Silindi';

        try {
            $reference = References::onlyTrashed()->findOrFail($id);
            $reference->forceDelete();
            $message = $reference->title . ' Referans Kalıcı Olarak Silindi';
            $deleteReference = $this->commonService->deleteFile(config("constants.references_path"), $reference->image);
            LogService::add("Reference Service ForceDelete", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Referans Kalıcı Olarak Silinemedi";
            LogService::add("Reference Service ForceDelete ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }

    }
    public function publish($id)
    {
        $status = "success";
        $message = "Referans Yayınlandı";
        try {
            $reference = References::findOrFail($id);
            if ($reference->published == 1) {
                $reference->update(["published" => 0]);
                $message = "Referans Yayından Kaldırıldı.";
            } else {
                $reference->update(["published" => 1]);
                $message = "Referans Yayınlandı";
            }
            LogService::add("Pop-Up Service Publish", $status, $reference->title . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Referans Yayın İşlemi Başarısız";
            LogService::add("Pop-Up Service Publish", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }


    public function showHomePage($id)
    {
        $status = "success";
        $message = "Referans Anasayfaya Eklendi";
        try {
            $reference = References::findOrFail($id);
            if ($reference->show_homepage == 1) {
                $reference->update(["show_homepage" => 0]);
                $message = "Referans Anasayfadan Kaldırıldı.";
            } else {
                $reference->update(["show_homepage" => 1]);
                $message = "Referans Anasayfada Yayınlandı.";
            }
            LogService::add("References Service Publish", $status, $reference->title . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Referans Anasayfa Ekleme İşlemi Başarısız";
            LogService::add("References Service Publish", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Referans Başarıyla Güncellendi";

        try {
            $references = References::findOrFail($id);

            // ======================================================
            // 1. IMAGE İŞLEMLERİ (Sayfa Resmi)
            // ======================================================
            if ($request->has('remove_image')) {
                if ($references->image) {
                    $this->commonService->deleteFile(config('constants.references_path'), $references->image);
                }
                $references->update(["image" => NULL]);

            }

            if ($request->hasFile('image')) {
                if ($references->image) {
                    $this->commonService->deleteFile(config('constants.references_path'), $references->image);
                }
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                // $references->slug yerine Str::slug($request->name) kullanıyoruz
                $fileName = Str::slug($request->name) . '-' . Str::lower(Str::random(4)) . '.' . $extension;
                $this->commonService->uploadFile(config('constants.references_path'), $imageFile, $fileName);
                $references->update(["image" => $fileName]);
            }

            $references->update([
                'sezon_id'=>$request->sezon_id,
                'type_id' => $request->type_id,
                'name' => $request->name,
                'hit' => $request->hit,
                'url' => $request->url,
                'lang_id' => $request->lang_id,
            ]);

            LogService::add("References Service Update", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Referens Güncellenemedi";
            LogService::add("References Service Update ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function bulkStore(Request $request)
    {
        $status = "success";
        $message = "Referanslar Kaydedildi";
        $successCount = 0;
        $failCount = 0;

        try {
            if (!$request->hasFile('images')) {
                return ["status" => "error", "message" => "Lütfen en az bir resim seçin."];
            }

            $files = $request->file('images');

            // Aynı türde mevcut maksimum hit değerini bul (sıralama otomatik devam etsin)
            $lastHit = References::where('type_id', $request->type_id)
                ->where('lang_id', $request->lang_id)
                ->max('hit') ?? 0;

            foreach ($files as $index => $imageFile) {
                try {
                    // Dosya adından otomatik referans adı üret (örn: "toyota-logo.png" → "Toyota Logo")
                    $originalName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $referenceName = Str::title(str_replace(['-', '_'], ' ', $originalName));

                    $slug = Str::slug($referenceName, "-");
                    $extension = $imageFile->guessExtension();
                    // Aynı isimde dosya çakışmasını önlemek için random ek
                    $image = $slug . '-' . Str::lower(Str::random(4)) . '.' . $extension;

                    $this->commonService->uploadFile(
                        config('constants.references_path'),
                        $imageFile,
                        $image
                    );

                    References::create([
                        'sezon_id' => $request->sezon_id,
                        'type_id'  => $request->type_id,
                        'name'     => $referenceName,
                        'image'    => $image,
                        'hit'      => $lastHit + ($index + 1),
                        'url'      => null,
                        'lang_id'  => $request->lang_id,
                    ]);

                    $successCount++;
                } catch (\Throwable $e) {
                    $failCount++;
                    LogService::add("Reference Service BulkStore (Item)", "error",
                        "Dosya yüklenemedi: " . $imageFile->getClientOriginalName() . " => " . $e->getMessage());
                }
            }

            $message = "{$successCount} referans başarıyla kaydedildi.";
            if ($failCount > 0) {
                $message .= " {$failCount} dosya yüklenemedi.";
            }

            LogService::add("Reference Service BulkStore", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Toplu yükleme başarısız";
            LogService::add("Reference Service BulkStore", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

}
