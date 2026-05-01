<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalkonVitrinBasvuru extends Model
{

    protected $fillable = [
        'tur', 'isletme_adi', 'ad_soyad', 'email', 'telefon',
        'adres', 'resit_mi', 'veli_izin_belgesi', 'fotograflar', 'kvkk_onay', 'ip_adresi',
        'spam_score',
        'is_spam',
        'spam_reasons',
        'user_agent',
    ];

    protected $casts = [
        'markRead' => 'boolean',
        'izin' => 'boolean',
        'is_spam' => 'boolean',
        'fotograflar' => 'array', // Veritabanındaki JSON veriyi otomatik diziye çevirir
    ];
}
