<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class References extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name', 'sezon_id', 'image', 'type_id', 'url', 'hit', 'published','show_homepage', 'lang_id','translation_of'];

    public function image()
    {
        if ($this->image) {
            return asset("storage/" . config('constants.references_path') . "/" . $this->image); // Resmin tam URL'sini döndürüyor
        }
        return false;
    }
    public function karnavalSezonus()
    {
        return $this->belongsTo(KarnavalSezonu::class, 'sezon_id');
    } public function type()
    {
        return $this->belongsTo(ReferenceType::class, 'type_id');
    }public function languages()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }

}
