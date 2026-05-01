<?php

namespace App\Services;

use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeatureService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function store(Request $request)
    {
        $status = "success";
        $message = "Özellikler Başarıyla Kaydedildi";

        try {
            // Inputları alıyoruz (Boş gelme ihtimallerine karşı varsayılan olarak boş dizi [] atıyoruz)
            $names = $request->input('name', []);
            $guns = $request->input('gun', []);
            $urls = $request->input('url', []);
            $contents = $request->input('content_text', []);
            $hits = $request->input('hit', []);
            $images = $request->file('image'); // Dosyalar

            // Sadece gerçekten bir isim dizisi (array) gönderilmişse döngüye gir
            if (!empty($names) && is_array($names)) {

                foreach ($names as $index => $name) {
                    // Eğer kullanıcı boş bir satır ekleyip ismini girmemişse o satırı atla
                    if (empty($name)) continue;

                    $slug = \Illuminate\Support\Str::slug($name, "-");
                    $imageFileName = NULL;

                    // Resim kontrolü (Sadece o indexte geçerli bir dosya yüklenmişse)
                    if (isset($images[$index]) && $images[$index]->isValid()) {
                        $imageFile = $images[$index];
                        $extension = $imageFile->guessExtension();
                        $imageFileName = $slug . '-' . \Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(4)) . "." . $extension;
                        $this->commonService->uploadFile(config('constants.features_path'), $imageFile, $imageFileName);
                    }

                    // Array Offset (Tanımsız Dizi İndeksi) hatalarını önlemek için her veriyi isset() ile kontrol ediyoruz
                    Feature::create([
                        'page_id' => $request->page_id,
                        'name'    => $name,
                        'gun'     => isset($guns[$index]) ? $guns[$index] : null,
                        'content' => isset($contents[$index]) ? $contents[$index] : null,
                        'image'   => $imageFileName,
                        'hit'     => isset($hits[$index]) && $hits[$index] !== "" ? $hits[$index] : 1,
                        'url'     => isset($urls[$index]) ? $urls[$index] : null,
                    ]);
                }
            }

            LogService::add("Feature Service Store", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Özellik Kaydedilemedi";
            // HATA LOGUNU GERİ EKLEDİK! Artık bir şey patlarsa Log kayıtlarında bas bas bağıracak.
            LogService::add("Feature Service Store", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Özellik Başarıyla Güncellendi";

        try {
            $feature = Feature::findOrFail($id);

            if ($request->has('remove_image')) {
                if ($feature->image) {
                    $this->commonService->deleteFile(config('constants.features_path'), $feature->image);
                }
                $feature->update(["image" => NULL]);
            }

            if ($request->hasFile('image')) {
                if ($feature->image) {
                    $this->commonService->deleteFile(config('constants.features_path'), $feature->image);
                }
                $imageFile = $request->file('image');
                $extension = $imageFile->guessExtension();
                $fileName = Str::slug($request->name) . '-' . Str::lower(Str::random(4)) . '.' . $extension;
                $this->commonService->uploadFile(config('constants.features_path'), $imageFile, $fileName);
                $feature->update(["image" => $fileName]);
            }

            $feature->update([
                'page_id' => $request->page_id,
                'name' => $request->name,
                'gun' => $request->gun,
                'content' => $request->content_text,
                'hit' => $request->hit,
                'url' => $request->url,
            ]);

            LogService::add("Feature Service Update", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            return ["status" => "error", "message" => "Özellik Güncellenemedi: " . $exception->getMessage()];
        }
    }

    public function destroy($id)
    {
        try {
            $feature = Feature::findOrFail($id);
            $feature->delete();
            return ["status" => "success", "message" => "Özellik Çöpe Atıldı"];
        } catch (\Throwable $exception) {
            return ["status" => "error", "message" => "Hata: " . $exception->getMessage()];
        }
    }

    public function restore($id)
    {
        try {
            $feature = Feature::onlyTrashed()->findOrFail($id);
            $feature->restore();
            return ["status" => "success", "message" => "Özellik Geri Yüklendi"];
        } catch (\Throwable $exception) {
            return ["status" => "error", "message" => "Hata: " . $exception->getMessage()];
        }
    }

    public function forceDelete($id)
    {
        try {
            $feature = Feature::onlyTrashed()->findOrFail($id);
            if ($feature->image) {
                $this->commonService->deleteFile(config("constants.features_path"), $feature->image);
            }
            $feature->forceDelete();
            return ["status" => "success", "message" => "Kalıcı Olarak Silindi"];
        } catch (\Throwable $exception) {
            return ["status" => "error", "message" => "Hata: " . $exception->getMessage()];
        }
    }

    public function publish($id)
    {
        try {
            $feature = Feature::findOrFail($id);
            $feature->update(["published" => !$feature->published]);
            $msg = $feature->published ? "Yayınlandı" : "Yayından Kaldırıldı";
            return ["status" => "success", "message" => $msg];
        } catch (\Throwable $exception) {
            return ["status" => "error", "message" => "Hata: " . $exception->getMessage()];
        }
    }
}
