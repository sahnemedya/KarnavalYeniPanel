<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'type', 'file', 'hit', 'published', 'lang_id'];

    public function image()
    {
        if ($this->image) {
            // Resmin tam URL'sini döndürüyor
            return asset("storage/" . config('constants.certificates_path') . "/" . $this->image);
        }
        return false;
    }

    public function file()
    {
        if ($this->file) {
            // Dosyanın tam URL'sini döndürüyor
            return asset("storage/" . config('constants.certificates_files_path') . "/" . $this->file);
        }
        return false;
    }

}
