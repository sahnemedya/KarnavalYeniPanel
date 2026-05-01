<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PressKit extends Model
{
    use HasFactory;

    protected $fillable = ["name","image","file","lang_id"];

    public function image()
    {
        if ($this->image) {
            // Resmin tam URL'sini döndürüyor
            return asset("storage/" . config('constants.press_kit_path') . "/" . $this->image);
        }
        return false;
    }

    public function file()
    {
        if ($this->file) {
            // Dosyanın tam URL'sini döndürüyor
            return asset("storage/" . config('constants.press_kit_files_path') . "/" . $this->file);
        }
        return false;
    }

}
