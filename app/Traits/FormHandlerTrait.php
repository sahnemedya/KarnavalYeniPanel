<?php

namespace App\Traits;

use App\Models\ApiKeys;
use App\Models\Contacts;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

trait FormHandlerTrait
{
    /**
     * Ortak Recaptcha Kontrolü
     *
     * - Local ortamda otomatik geçer (geliştirme kolaylığı).
     * - Production'da Google reCAPTCHA v3 doğrulaması yapar.
     * - cURL yerine Laravel HTTP Client kullanır (timeout + exception güvenliği).
     */
    private function checkRecaptcha($response, $ip)
    {
        // 1. LOCAL BYPASS — Geliştirme ortamında reCAPTCHA çağrısı atlanır
        if (app()->environment('local')) {
            Log::channel('userLog')->info("Recaptcha kontrolü atlandı (local env). IP: $ip");
            return true;
        }

        // 2. Production: Token boşsa direkt reddet
        if (empty($response)) {
            Log::channel('userLog')->warning("Recaptcha token boş. IP: $ip");
            return false;
        }

        // 3. Secret key var mı?
        $key = ApiKeys::select('recaptcha_secret_key')->first();
        if (!$key || empty($key->recaptcha_secret_key)) {
            Log::error('Recaptcha secret key veritabanında tanımlı değil!');
            return false;
        }

        // 4. Google API'ye doğrulama isteği (timeout + try/catch ile güvenli)
        try {
            $verify = Http::timeout(8)
                ->asForm()
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => $key->recaptcha_secret_key,
                    'response' => $response,
                    'remoteip' => $ip,
                ]);

            if (!$verify->ok()) {
                Log::warning("Recaptcha API HTTP hatası. Status: {$verify->status()} | IP: $ip");
                return false;
            }

            $result = $verify->object();

            if (empty($result->success)) {
                return false;
            }

            // v3 ise score kontrolü, v2 ise sadece success yeterli
            return isset($result->score) ? $result->score >= 0.7 : true;

        } catch (\Throwable $e) {
            Log::error('Recaptcha doğrulama exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Recaptcha doğrulaması yapar; başarısızsa hazır redirect döner, başarılıysa null.
     * Controller'lardaki tekrarı azaltır.
     *
     * Kullanım:
     *   if ($redirect = $this->guardRecaptcha($request)) return $redirect;
     */
    private function guardRecaptcha($request)
    {
        if (!$this->checkRecaptcha($request->g_recaptcha_response, $request->ip())) {
            Alert::error('Hata', 'Güvenlik doğrulaması başarısız. Lütfen tekrar deneyin.');
            return redirect()->back()->withInput();
        }
        return null;
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
            Log::channel('userLog')->warning(
                "$logTitle SPAM ENGELLENDİ | IP: " . request()->ip() .
                " | Score: " . ($spamResult['score'] ?? 0) .
                " | Reasons: " . json_encode($spamResult['reasons'] ?? [], JSON_UNESCAPED_UNICODE)
            );
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
