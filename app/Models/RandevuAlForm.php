<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RandevuAlForm extends Model
{
    use HasFactory;
    protected $fillable = ['adSoyad','telefon','email','birim','doktor','tarih','saat','mesaj', 'markRead', 'izin', 'kvkk'];
}
