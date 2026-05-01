<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortakalliLezzetler extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ad',
        'tc',
        'dogum',
        'adres',
        'telefon',
        'mail',
        'meslek',
        'yemekadi',
        'yemekmalzemesi',
        'yemektarifi',
        'bilgi',
        'markRead',
        'spam_score',
        'is_spam',
        'spam_reasons',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'markRead' => 'boolean',
        'izin' => 'boolean',
        'is_spam' => 'boolean',
    ];
}
