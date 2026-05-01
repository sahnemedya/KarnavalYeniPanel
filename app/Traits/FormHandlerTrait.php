<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\ApiKeys;
use App\Models\Contacts;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

trait FormHandlerTrait
{
    /**
     * Ortak Recaptcha Kontrolü
     */
    private function checkRecaptcha($response, $ip)
    {
        $key = ApiKeys::select('recaptcha_secret_key')->first();
        if (!$key || empty($response)) return false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify?secret={$key->recaptcha_secret_key}&response={$response}&remoteip={$ip}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        return ($result->success && (isset($result->score) ? $result->score >= 0.7 : true));
    }

    /**
     * Ortak Kayıt ve Mail Süreci (Refactored finalizeSubmission)
     */
    private function processFormSubmission($model, $mailableClass, $mailData, $logTitle, $spamResult)
    {
        // 1. Veritabanına Kaydet (Spam olsa da olmasa da kayıt tutuyoruz)
        if (!$model->save()) {
            Log::error("$logTitle: Veritabanı kayıt hatası!");
            Alert::error('Hata!', 'Mesajınız kaydedilemedi.');
            return redirect()->back();
        }

        // 2. Spam veya Şüpheli ise Mail Atma (Siber Güvenlik Koruması)
        if ($spamResult['blocked'] || $spamResult['suspicious']) {
            Log::channel('userLog')->warning("$logTitle SPAM ENGELLENDİ: IP: " . request()->ip());
            Alert::success('Başarılı', 'İşleminiz alınmıştır.'); // Botları yanıltmak için başarılı diyoruz
            return redirect()->back();
        }

        // 3. Mail Gönderimi (Queue önerilir)
        try {
            $contact = Contacts::find(1);
            $recipients = array_filter([$contact->email ?? null, "firmamailformlari@sahnemedya.com"]);

            // Mail::to(...)->queue(...) kullanman performansı %300 artırır
            Mail::to($recipients)->send(new $mailableClass($mailData));

            Log::channel('userLog')->info("$logTitle Başarılı: " . request()->ip());
            Alert::success('Başarılı', 'Mesajınız başarıyla gönderildi.');
        } catch (\Exception $e) {
            Log::error("$logTitle Mail Hatası: " . $e->getMessage());
            Alert::warning('Uyarı', 'Mesajınız kaydedildi ancak mail iletilemedi.');
        }

        return redirect()->back();
    }
}
