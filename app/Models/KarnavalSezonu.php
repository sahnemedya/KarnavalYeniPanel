<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
class KarnavalSezonu extends Model
{
    Use HasFactory, SoftDeletes;
    protected $fillable = ['karnaval_tarihi_baslangic','karnaval_tarihi_bitis','sezon_baslangici','karnaval_yili','published', 'hit'];

    public function references(): HasMany
    {
        return $this->hasMany(References::class, 'sezon_id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'sezon_id');
    }

}
