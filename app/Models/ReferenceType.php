<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class ReferenceType extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = ['name', 'hit',  'lang_id'];

    public function languages()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }

    public function translations()
    {
        return $this->hasMany(ReferenceType::class, 'translation_of');
    }

    public function parentTranslation()
    {
        return $this->belongsTo(ReferenceType::class, 'translation_of');
    }

    /**
     * Aktif dile göre bu türün ismini döndürür.
     * Çeviri yoksa kendi adını verir (graceful fallback).
     */
    public function localizedName($langId = null)
    {
        $langId = $langId ?? Session::get('uLangId') ?? 1;

        if ($this->lang_id == $langId) {
            return $this->name;
        }

        // Bu kayıt parent ise, child translation'ı ara
        if (is_null($this->translation_of)) {
            $translation = $this->translations()->where('lang_id', $langId)->first();
            return $translation?->name ?? $this->name;
        }

        // Bu kayıt child ise, kardeş translation'ı veya parent'ı ara
        $sibling = static::where('translation_of', $this->translation_of)
            ->where('lang_id', $langId)
            ->first();

        if ($sibling) return $sibling->name;

        if ($this->parentTranslation && $this->parentTranslation->lang_id == $langId) {
            return $this->parentTranslation->name;
        }

        return $this->name;
    }
}
