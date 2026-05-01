<?php

namespace App\Services;

use App\Models\AramaKayit;
use Illuminate\Support\Facades\Log;

class AramaKayitService
{
    public function kayitEkle(array $data): AramaKayit
    {
        $tur = ($data['arama'] ?? '') == 'wp' ? 'Whatsapp' : 'Telefon';

        // Spam Tıklama Koruması:
        // Bu IP, bu türde, son 1 dakika içinde kayıt atmış mı?
        $sonKayit = AramaKayit::where('ip', $data['ip'])
            ->where('tur', $tur)
            ->where('created_at', '>=', now()->subMinute())
            ->first();

        if ($sonKayit) {
            // Zaten yeni tıklamış, veritabanını şişirme, mevcut kaydı döndür.
            return $sonKayit;
        }

        $arama = new AramaKayit();
        $arama->ip = $data['ip'];
        $arama->tur = $tur;
        $arama->save();

        return $arama;
    }
}
