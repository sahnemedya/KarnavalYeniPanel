<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BultenIletisimFormu extends Model
{
    use HasFactory;

    protected $fillable = [
        'adSoyad','telefon','email','konu','mesaj','markRead','izin',
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
