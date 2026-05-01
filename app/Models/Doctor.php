<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["title", "description","slug", "content", 'medical_unit', 'medical_unit2', "image", "image2", "show","show_homepage","hit"];

//    public function doctor()
//    {
//        return $this->doctor_title . " " . $this->name . " " . $this->surname;
//    }

    public function image()
    {
        if ($this->image) {
            return asset("storage/" . config('constants.doctor_path') . "/" . $this->image); // Resmin tam URL'sini döndürüyor
        }
        return false;
    }

    public function image2()
    {
        if ($this->image2) {
            return asset("storage/" . config('constants.doctor_path') . "/" . $this->image2); // Resmin tam URL'sini döndürüyor
        }
        return false;
    }

    public function medicalUnit()
    {
        return $this->belongsTo(Page::class, "medical_unit")->withTrashed();
    }

    public function medicalUnit2()
    {
        return $this->belongsTo(Page::class, "medical_unit2")->withTrashed();
    }

}
