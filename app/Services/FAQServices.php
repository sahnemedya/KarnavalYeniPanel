<?php

namespace App\Services;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FAQServices
{
    protected CommonService $commonService;

    function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }
// ... (Mevcut kodların üst kısmı) ...

    // --- YENİ EKLENEN INDEX METODU ---
    public function index()
    {
        // URL'den gelen lang_id'yi al, yoksa 1 (TR) kabul et
        $langId = request('lang_id', 1);

        // Seçilen dile göre filtrele
        $faqs = FAQ::where('lang_id', $langId)->get();

        // Sidebar için dilleri çek
        $languages = \App\Models\Language::all();

        return [
            'faqs' => $faqs,
            'languages' => $languages
        ];
    }

    // ... (Mevcut store ve destroy metodların aynen kalsın) ...
    public function store(Request $request)
    {
        $status = "success";
        // Kaç adet eklendiğini mesajda belirtmek şık olur
        $count = is_array($request->question) ? count($request->question) : 0;
        $message = "$count Adet Sıkça Sorulan Soru Kayıt Edildi";

        try {
            // Gelen verinin dizi olup olmadığını kontrol ediyoruz
            if ($request->has('question') && is_array($request->question)) {

                // Sorular dizisi üzerinde dönüyoruz
                foreach ($request->question as $key => $questionText) {

                    // Eğer soru boşsa atla (Boş satır kaydetmemek için güvenlik)
                    if (empty($questionText)) {
                        continue;
                    }

                    FAQ::create([
                        'question' => $questionText,
                        // $key index'ini kullanarak o sıradaki cevabı ve sırayı alıyoruz
                        'answer'   => $request->answer[$key] ?? null,
                        'hit'      => $request->hit[$key] ?? ($key + 1), // Eğer hit boş gelirse otomatik sıra ver
                        'page_id'  => $request->page_id, // Bu alanlar tüm sorular için ortaktır
                        'lang_id'  => $request->lang_id  // Bu alanlar tüm sorular için ortaktır
                    ]);
                }
            }

            LogService::add("FAQ Store", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Sıkça Sorulan Sorular Kaydedilemedi";
            LogService::add("FAQ Store", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroy($id)
    {
        $status = "success";
        $message = "Sıkça Sorulan Soru Silindi";

        try {
            $faqs = FAQ::findOrFail($id);

            $faqs->delete();

            LogService::add("FAQ Services Destroy", $status, $faqs->question . " " . $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Sıkça Sorulan Soru Silinemedi";
            LogService::add("FAQ Services Destroy", $status, $message . " => " . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }
}
