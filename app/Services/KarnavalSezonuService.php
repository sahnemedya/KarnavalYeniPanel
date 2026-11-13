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

    /**
     * Sonraki yılın karnaval sezonunu hızlıca oluşturur.
     *
     * Son kayıtlı sezondan +1 yıla ait tarihleri türetir (karnaval kalıbı
     * değişmez varsayımıyla). Panelde "Hızlı Sonraki Yıl Ekle" butonu
     * ya da artisan komutu (karnaval:yeni-sezon) tarafından çağrılır.
     *
     * @param  int|null  $hedefYil   Belirli bir yıl ekleneceği zaman geçilir, yoksa son+1
     * @param  bool      $aktifYap   true ise diğer sezonlar pasife çekilip bu aktif edilir
     * @return array     ['status' => ..., 'message' => ..., 'data' => KarnavalSezonu?]
     */
    public function sonrakiYiliEkle(?int $hedefYil = null, bool $aktifYap = false): array
    {
        $status = "success";

        try {
            $sonSezon = KarnavalSezonu::orderBy('karnaval_yili', 'desc')->first();

            if (!$hedefYil) {
                $hedefYil = $sonSezon
                    ? ((int) $sonSezon->karnaval_yili) + 1
                    : (\Carbon\Carbon::now()->year + 1);
            }

            if ($hedefYil < 2000 || $hedefYil > 2100) {
                return ["status" => "error", "message" => "Geçersiz yıl: {$hedefYil}"];
            }

            // Mevcut mu? (soft-delete dahil)
            $mevcut = KarnavalSezonu::withTrashed()
                ->where('karnaval_yili', (string) $hedefYil)
                ->first();

            if ($mevcut) {
                return [
                    "status"  => "warning",
                    "message" => "'{$hedefYil}' yılı zaten kayıtlı" . ($mevcut->trashed() ? ' (silinmiş).' : '.'),
                ];
            }

            // Son sezondan +1 yıl ileriye alarak tarihleri türet
            if ($sonSezon) {
                $baslangic = \Carbon\Carbon::parse($sonSezon->karnaval_tarihi_baslangic)->addYear()->format('Y-m-d');
                $bitis     = \Carbon\Carbon::parse($sonSezon->karnaval_tarihi_bitis)->addYear()->format('Y-m-d');
                $sezonBas  = \Carbon\Carbon::parse($sonSezon->sezon_baslangici)->addYear()->format('Y-m-d');
                $hit       = (int) ($sonSezon->hit ?? 0) + 2;
            } else {
                $baslangic = \Carbon\Carbon::create($hedefYil, 4, 1)->format('Y-m-d');
                $bitis     = \Carbon\Carbon::create($hedefYil, 4, 7)->format('Y-m-d');
                $sezonBas  = \Carbon\Carbon::create($hedefYil - 1, 10, 1)->format('Y-m-d');
                $hit       = 2;
            }

            $yeni = KarnavalSezonu::create([
                'hit'                       => $hit,
                'karnaval_yili'             => (string) $hedefYil,
                'sezon_baslangici'          => $sezonBas,
                'karnaval_tarihi_baslangic' => $baslangic,
                'karnaval_tarihi_bitis'     => $bitis,
                'published'                 => false,
            ]);

            if ($aktifYap) {
                KarnavalSezonu::where('id', '!=', $yeni->id)->update(['published' => 0]);
                $yeni->update(['published' => true]);
            }

            LogService::add(
                "Karnaval Sezonu Sonraki Yıl Ekle",
                $status,
                "{$hedefYil} sezonu eklendi (id: {$yeni->id})" . ($aktifYap ? ' + aktif' : '')
            );

            return [
                "status"  => $status,
                "message" => "'{$hedefYil}' karnaval sezonu oluşturuldu. Tarihleri gözden geçirmeyi unutma.",
                "data"    => $yeni,
            ];

        } catch (\Throwable $exception) {
            LogService::add(
                "Karnaval Sezonu Sonraki Yıl Ekle Error",
                "error",
                $exception->getMessage()
            );
            return [
                "status"  => "error",
                "message" => "Sonraki yıl eklenemedi: " . $exception->getMessage(),
            ];
        }
    }
}
