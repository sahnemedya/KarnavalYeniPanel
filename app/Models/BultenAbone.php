<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BultenAbone extends Model
{
    use HasFactory;

    protected $fillable = [
        'adSoyad',
        'telefon',
        'email',
        'spam_score',
        'is_spam',
        'spam_reasons',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_spam' => 'boolean',
    ];
}
