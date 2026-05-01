<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'page_id','hit'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function image()
    {
        if ($this->image) {
            // Resmin tam URL'sini döndürüyor
            return asset("storage/" . config('constants.gallery_path') . "/" . $this->image);
        }
        return false;
    }
}
