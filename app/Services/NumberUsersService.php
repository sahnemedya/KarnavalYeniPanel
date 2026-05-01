<?php

namespace App\Services;

use App\Models\NumberUsers;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;

class NumberUsersService
{
    // ENGELLENECEK ÜLKE KODLARI
    // Bu liste, hastanenin hedef kitlesi olmayan ve botların (Data Center) yoğun olduğu ülkelerdir.
    protected $blockedCountries = [
        'US', // Amerika (Google, AWS, Microsoft botlarının %90'ı buradan gelir)
        'CN', // Çin (Tarayıcı botları)
        'RU', // Rusya
        'IN', // Hindistan
        'SG', // Singapur (Data Center yoğunluğu)
        'IE', // İrlanda (AWS Avrupa sunucuları)
        'GB', // İngiltere (Eğer İngiltere'den hasta beklemiyorsanız kapatın, bekliyorsanız listeden çıkarın)
    ];

    public function kullaniciKaydet(string $ip, string $cihaz, string $userAgent): array
    {
        // 1. USER-AGENT BOT KONTROLÜ (Yazılımsal Botlar)
        if ($this->botMu($userAgent)) {
            return ['status' => 'ignored', 'message' => 'Bot trafiği engellendi (User-Agent).'];
        }

        try {
            // 2. Cookie Kontrolü
            if (Cookie::get('user_ip')) {
                return ['status' => 'info', 'message' => 'Kullanıcı cookie ile tanındı.'];
            }

            // 3. Veritabanı Kontrolü (Bugün kaydedilmiş mi?)
            $bugunKayitVarMi = NumberUsers::where('ip', $ip)
                ->whereDate('created_at', \Carbon\Carbon::today())
                ->exists();

            if ($bugunKayitVarMi) {
                Cookie::queue('user_ip', $ip, 1440);
                return ['status' => 'info', 'message' => 'Kullanıcı veritabanında zaten var.'];
            }

            // 4. KONUM BULMA VE ÜLKE FİLTRESİ (Coğrafi Botlar)
            $ulke = null;
            $sehir = null;
            $ulkeKodu = null;

            if ($position = Location::get($ip)) {
                $ulke = $position->countryName;
                $sehir = $position->cityName;
                $ulkeKodu = $position->countryCode;

                // --- KRİTİK NOKTA: ENGEL LİSTESİ KONTROLÜ ---
                // Eğer gelen IP yasaklı ülkelerden birindense (Örn: US), veritabanına kaydetme.
                if ($ulkeKodu && in_array($ulkeKodu, $this->blockedCountries)) {
                    return ['status' => 'ignored', 'message' => "Engellenen ülke trafiği: $ulke ($ulkeKodu)"];
                }
            }

            // 5. KAYIT İŞLEMİ (Sadece Temiz Trafik)
            NumberUsers::create([
                'ip' => $ip,
                'cihaz' => $cihaz,
                'ulke' => $ulke,
                'sehir' => $sehir,
                'ulke_kodu' => $ulkeKodu
            ]);

            Cookie::queue('user_ip', $ip, 1440);

            return ['status' => 'success', 'message' => 'Yeni kullanıcı kaydedildi'];

        } catch (\Exception $e) {
            Log::error('NumberUsersService Hata:', [
                'ip' => $ip,
                'error' => $e->getMessage()
            ]);

            return ['status' => 'error', 'message' => 'Sistem hatası'];
        }
    }

    public function cihazTespiti(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);

        if (strpos($userAgent, 'tablet') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'Tablet';
        } elseif (strpos($userAgent, 'mobile') !== false || strpos($userAgent, 'android') !== false || strpos($userAgent, 'iphone') !== false) {
            return 'Mobil';
        } else {
            return 'Masaüstü';
        }
    }

    private function botMu(string $userAgent): bool
    {
        if (empty($userAgent)) {
            return true;
        }

        $userAgentLower = strtolower($userAgent);

        $botPatterns = [
            'bot', 'crawl', 'slurp', 'spider', 'mediapartners',
            'facebookexternalhit', 'whatsapp', 'telegram', 'twitterbot', 'linkedin', 'slack',
            'google', 'bing', 'yandex', 'baidu', 'duckduckgo', 'sogou', 'exabot',
            'ia_archiver', 'semrush', 'ahrefs', 'mj12bot', 'dotbot', 'rogertbot', 'seo', 'sistrix',
            'python', 'curl', 'wget', 'httpclient', 'postman', 'axios', 'guzzle', 'java/',
            'uptime', 'monitor', 'gtmetrix', 'lighthouse', 'pagespeed',
            'headless', 'phantomjs', 'selenium', 'puppeteer'
        ];

        foreach ($botPatterns as $pattern) {
            if (strpos($userAgentLower, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }
}
