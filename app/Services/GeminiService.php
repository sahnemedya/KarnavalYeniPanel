<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        // Stabilite için 1.5-flash kullanımı önerilir
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';
    }

    public function translateContent(string $title, string $content, string $targetLanguage): array
    {
        if (empty($title) && empty($content)) {
            return ['status' => 'warning', 'title' => 'İçerik Boş', 'content' => 'Çevrilecek bir metin bulunamadı.'];
        }

        $prompt = "Aşağıdaki JSON verisini {$targetLanguage} diline çevir. HTML yapılarını koru. Sadece saf JSON dön.\n"
            . json_encode(['title' => $title, 'content' => $content], JSON_UNESCAPED_UNICODE);

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(45)
                ->post("{$this->baseUrl}?key={$this->apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => [
                        'response_mime_type' => 'application/json', // API seviyesinde JSON zorlaması
                    ]
                ]);

            if ($response->failed()) {
                return $this->handleApiError($response);
            }

            $responseData = $response->json();

            // 1. Durum: Güvenlik Filtresi (Hate speech, Harassment vb.)
            if (isset($responseData['candidates'][0]['finishReason']) && $responseData['candidates'][0]['finishReason'] === 'SAFETY') {
                return [
                    'status' => 'safety',
                    'title' => 'İçerik Engellendi',
                    'content' => 'Metin, güvenlik filtrelerine takıldığı için çevrilemedi.'
                ];
            }

            $rawText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$rawText) {
                return ['status' => 'error', 'title' => 'Boş Yanıt', 'content' => 'AI geçerli bir yanıt üretemedi.'];
            }

            // JSON Temizleme ve Decode
            $decoded = json_decode(trim(preg_replace('/json|/', '', $rawText)), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['status' => 'error', 'title' => 'Format Bozuk', 'content' => 'Dönen veri okunabilir JSON formatında değil.'];
            }

            return [
                'status' => 'success',
                'title' => $decoded['title'] ?? $title,
                'content' => $decoded['content'] ?? $content
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return ['status' => 'error', 'title' => 'Zaman Aşımı', 'content' => 'Google sunucuları çok geç yanıt veriyor.'];
        } catch (\Exception $e) {
            Log::error('Gemini Beklenmedik Hata: ' . $e->getMessage());
            return ['status' => 'error', 'title' => 'Sistem Hatası', 'content' => 'Beklenmedik bir sorun oluştu.'];
        }
    }

    private function handleApiError($response): array
    {
        $status = $response->status();
        $error = $response->json()['error'] ?? [];
        $message = $error['message'] ?? 'Bilinmeyen hata.';

        return match ($status) {
            429 => [
                'status' => 'limit',
                'title' => 'Kota Doldu',
                'content' => 'Günlük veya dakikalık çeviri limitiniz bitti. Lütfen bekleyin.'
            ],
            400 => [
                'status' => 'error',
                'title' => 'Geçersiz İstek',
                'content' => 'Gönderilen veri çok büyük olabilir veya format hatalıdır.'
            ],
            404 => [
                'status' => 'error',
                'title' => 'Model Bulunamadı',
                'content' => 'Kullanılan yapay zeka modeli (flash-latest) şu an hizmet dışı.'
            ],
            default => [
                'status' => 'error',
                'title' => "API Hatası ($status)",
                'content' => $message
            ]
        };
    }
}
