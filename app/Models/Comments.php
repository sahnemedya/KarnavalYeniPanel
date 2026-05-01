<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comments extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name', 'content', 'image', 'hit', 'published', 'lang_id'];

    public function image()
    {
        if ($this->image) {
            return asset("storage/" . config('constants.comments_path') . "/" . $this->image); // Resmin tam URL'sini döndürüyor
        }
        return false;
    }
    public function languages()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }

}
