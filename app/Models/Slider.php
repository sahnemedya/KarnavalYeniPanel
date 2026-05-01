<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','image','mobile_image','url','published','hit','lang_id'];

    public function getImagePath()
    {
        return asset('storage/' . Config::get('constants.slide_path'));
    }
    public function image()
    {
        if ($this->image) {
            return asset("storage/" . config('constants.slide_path') . "/" . $this->image); // Resmin tam URL'sini döndürüyor
        }
        return false;
    }

    public function getMobileImagePath()
    {
        return asset('storage/' . Config::get('constants.slide_path'));
    }

    public function mobilImage()
    {
        if ($this->mobile_image) {
            return asset("storage/" . config('constants.slide_path') . "/" . $this->mobile_image); // Resmin tam URL'sini döndürüyor
        }
        return false;
    }
}
