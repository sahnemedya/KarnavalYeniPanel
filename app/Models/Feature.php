<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name', 'gun', 'image', 'published', 'page_id', 'url', 'hit', 'content'];

    public function image()
    {
        if ($this->image) {
            return asset("storage/" . config('constants.features_path') . "/" . $this->image); // Resmin tam URL'sini döndürüyor
        }
        return false;
    }
    public function pages()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

}
