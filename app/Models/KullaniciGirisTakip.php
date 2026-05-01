<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KullaniciGirisTakip extends Model
{
    use HasFactory;

    public function getUserName(){
        return $this->hasOne(User::class,'id','kullanici_id');
    }
}
