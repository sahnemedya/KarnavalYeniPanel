<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AntiSpamService
{
    // Yasaklı kelime puanları
    protected array $badPatterns = [
        'click here' => 40, 'seo' => 40, 'backlink' => 50, 'casino' => 60,
        'betting' => 60, 'crypto' => 40, 'vulnerability' => 50,
        'identify' => 30, 'free try' => 50, 'income' => 30
    ];

    public function inspect(Request $request, string $formType = 'contact'): array
    {
        $score = 0;
        $reasons = [];

        // 1. Geliştirici Beyaz Listesi (Whitelist)
        if ($request->input('email') === 'info@sahnemedya.com') {
            return ['score' => 0, 'blocked' => false, 'suspicious' => false, 'reasons' => []];
        }

        // 2. Amerika (+1) Numarası Kontrolü (Geri Eklendi)
        // ulkeKodu "1" ise veya telefon başında +1/001 varsa direkt blokla.
        $ulkeKodu = (string) $request->input('ulkeKodu');
        $telefon = (string) $request->input('telefon');
        $telefonClean = preg_replace('/[^\d+]/', '', $telefon);

        if ($ulkeKodu === '1' || str_starts_with($telefonClean, '+1') || str_starts_with($telefonClean, '001')) {
            $score += 200; // Kesin blok
            $reasons[] = 'Amerika (+1) kaynaklı gönderim kısıtlaması.';
        }

        // 3. Veri Yapısı Kontrolü
        if ($this->isDataIntegrityViolated($request)) {
            $score += 100;
            $reasons[] = 'Veri bütünlüğü ihlali (Alan boyutu veya karakter hatası)';
        }

        // 4. Rate Limiter (10 dakikada 1 izin verir, 2. denemede +50 puan ekler)
        $rateKey = $formType . ':heavy:' . sha1($request->ip());
        if (RateLimiter::tooManyAttempts($rateKey, 1)) {
            $score += 50;
            $reasons[] = 'Kısa sürede tekrarlı istek.';
        }
        RateLimiter::hit($rateKey, 600);

        // 5. Honeypot
        if ($request->filled('website_url')) {
            $score += 200;
            $reasons[] = 'Honeypot tetiklendi.';
        }

        // 6. Pattern Analizi
        $patternResult = $this->containsSpamPattern($request->all());
        if ($patternResult['score'] > 0) {
            $score += $patternResult['score'];
            foreach($patternResult['matched'] as $m) $reasons[] = "Şüpheli içerik: $m";
        }

        // 7. Form Hızı
        $formTime = (int) $request->input('form_time', 0);
        $duration = time() - $formTime;
        if ($duration < 4) {
            $score += 40;
            $reasons[] = "Hızlı gönderim ($duration sn).";
        }

        return [
            'score' => $score,
            'blocked' => $score >= 120,
            'suspicious' => $score >= 60,
            'reasons' => $reasons,
        ];
    }

    protected function isDataIntegrityViolated(Request $request): bool
    {
        // Tarih kontrolü
        $tarih = (string) $request->input('tarih');
        if ($tarih && (strlen($tarih) > 12 || !preg_match('/^[0-9.-]+$/', $tarih))) {
            return true;
        }

        // Saat kontrolü
        $saat = (string) $request->input('saat');
        if ($saat && strlen($saat) > 10) return true;

        // Ad Soyad içinde URL/HTML var mı?
        if (preg_match('/http|www|href|<\//i', (string)$request->input('adSoyad'))) return true;

        return false;
    }

    protected function containsSpamPattern(array $inputs): array
    {
        $totalScore = 0;
        $matched = [];
        $checkText = ($inputs['mesaj'] ?? '') . ' ' . ($inputs['adSoyad'] ?? '');
        $checkText = mb_strtolower($checkText);

        foreach ($this->badPatterns as $pattern => $points) {
            if (str_contains($checkText, $pattern)) {
                $totalScore += $points;
                $matched[] = $pattern;
            }
        }
        return ['score' => $totalScore, 'matched' => $matched];
    }
}
