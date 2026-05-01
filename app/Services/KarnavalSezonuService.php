<?php

namespace App\Services;

use App\Models\KarnavalSezonu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class KarnavalSezonuService
{
    protected CommonService $commonService;
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
        //$this->commonService-> olarak kullanılacak
    }

    public function store(Request $request)
    {
        // Başlangıç değerlerimizi AJAX veya Controller yönlendirmesi için tanımlıyoruz
        $status = "success";
        $message = "Karnaval Sezonu Başarıyla Kaydedildi";

        try {
            // Blade formumuzdan gelen verileri, migration'da belirlediğimiz sütunlarla eşleştiriyoruz
            $karnavalSezonu = KarnavalSezonu::create([
                'hit'                       => $request->hit,
                'karnaval_yili'             => $request->karnaval_yili,
                'sezon_baslangici'          => $request->sezon_baslangici,
                'karnaval_tarihi_baslangic' => $request->karnaval_tarihi_baslangic,
                'karnaval_tarihi_bitis'     => $request->karnaval_tarihi_bitis,
                // Checkbox html formundan sadece işaretliyse gelir. has() metodu ile kontrol edip 1 veya 0 yazdırıyoruz.
            ]);

            // İşlem başarılıysa status ve message değerlerini döndür
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            // Bir hata olursa değerleri error olarak güncelle
            $status = "error";
            $message = "Karnaval Sezonu Kaydedilemedi";

            // Senin log mekanizman: Hatayı veritabanına veya dosyaya yazdır
            LogService::add("KarnavalSezonu Service Store ", $status, $message . ' => ' . $exception->getMessage());

            // Hata durumunu ve mesajı geri döndür
            return ["status" => $status, "message" => $message];
        }
    }

    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Karnaval Sezonu Başarıyla Güncellendi";

        try {
            $karnavalSezonu = KarnavalSezonu::findOrFail($id);
//
            $karnavalSezonu->update([
                'hit'                       => $request->hit,
                'karnaval_yili'             => $request->karnaval_yili,
                'sezon_baslangici'          => $request->sezon_baslangici,
                'karnaval_tarihi_baslangic' => $request->karnaval_tarihi_baslangic,
                'karnaval_tarihi_bitis'     => $request->karnaval_tarihi_bitis,
            ]);

            LogService::add("KarnavalSezonu Service Update", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "KarnavalSezonu Güncellenemedi";
            LogService::add("KarnavalSezonu Service Update ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }

    }

    public function destroy($id)
    {
        $status = "success";
        $message = 'Karnaval Sezonu Silindi';
        try {
            $karnavalSezonu = KarnavalSezonu::findOrFail($id);
            $karnavalSezonu->delete();
            $message = $karnavalSezonu->title . ' Karnaval Sezonu Silindi';
            LogService::add("Karnaval Sezonu Service Destroy", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Karnaval Sezonu Silinemedi";
            LogService::add("Karnaval Sezonu Service Destroy ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function restore($id)
    {
        $status = "success";
        $message = 'Karnaval Sezonu Geri Yüklendi';
        try {
            $karnavalSezonlari = KarnavalSezonu::onlyTrashed()->findOrFail($id);
            $karnavalSezonlari->restore();
            $message = $karnavalSezonlari->title . ' ' . $message;
            LogService::add("Karnaval Sezonu Service Restore", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Karnaval Sezonu Geri Yüklenemedi";
            LogService::add("Karnaval Sezonu Service Restore ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }

    }

    public function forceDelete($id)
    {
        $status = "success";
        $message = 'Karnaval Sezonu Silindi';

        try {
            $karnavalSezonu = KarnavalSezonu::onlyTrashed()->findOrFail($id);
            $karnavalSezonu->forceDelete();
            $message = $karnavalSezonu->title . ' Karnaval Sezonu Silindi';
            LogService::add("Karnaval Sezonu Service ForceDelete", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Karnaval Sezonu Silinemedi";
            LogService::add("Karnaval Sezonu Service ForceDelete ", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }

    }

//    public function activate($id)
//    {
//        $status = "success";
//        $message = "Karnaval Sezonu Aktif Edildi.";
//        try {
//            $karnavalSezonu = KarnavalSezonu::findOrFail($id);
//            if ($karnavalSezonu->published == 1) {
//                $karnavalSezonu->update(["published" => 0]);
//                $message = "Karnaval Sezonu Aktifliği Kaldırıldı.";
//            } else {
//                $karnavalSezonu->update(["published" => 1]);
//                $message = "Karnaval Sezonu Aktif Edildi.";
//            }
//            LogService::add("Karnaval Sezonu Aktif Edildi.", $status, $karnavalSezonu->title . " " . $message);
//            return ["status" => $status, "message" => $message];
//        } catch (\Throwable $exception) {
//            $status = "error";
//            $message = "Karnaval Sezonu Aktifliği Kaldırıldı.";
//            LogService::add("Karnaval Sezonu Aktiflik Service Publish", $status, $message . " => " . $exception->getMessage());
//            return ["status" => $status, "message" => $message];
//        }
//    }
    public function activate($id)
    {
        $status = "success";
        $message = "Karnaval Sezonu Aktif Edildi.";
        try {
            $karnavalSezonu = KarnavalSezonu::findOrFail($id);

            if ($karnavalSezonu->published == 1) {
                // Eğer zaten aktifse, sadece bunu pasife çekiyoruz.
                $karnavalSezonu->update(["published" => 0]);
                $message = "Karnaval Sezonu Aktifliği Kaldırıldı.";
            } else {
                // Aktif edilecekse, önce diğer tüm kayıtları pasife çekiyoruz.
                KarnavalSezonu::where('id', '!=', $id)->update(['published' => 0]);

                // Sonra seçilen kaydı aktif ediyoruz.
                $karnavalSezonu->update(["published" => 1]);
                $message = "Karnaval Sezonu Aktif Edildi.";
            }

            LogService::add("Karnaval Sezonu Aktif Edildi.", $status, $karnavalSezonu->title . " " . $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "İşlem sırasında bir hata oluştu."; // Orijinalindeki mantık hatasını düzeltmek için mesajı değiştirdim.
            LogService::add("Karnaval Sezonu Aktiflik Service Publish", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }
}
