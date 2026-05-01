<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class NumberUsers extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'ip',
        'cihaz',
        'ulke',
        'sehir',
        'ulke_kodu'
    ];
}
