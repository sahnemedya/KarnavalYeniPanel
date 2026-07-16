<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Award extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'image', 'prize_date', 'hit', 'published'];

    protected $casts = [
        'prize_date' => 'date',
    ];

    public function image()
    {
        if ($this->image) {
            // Resmin tam URL'sini döndürüyor
            return asset("storage/" . config('constants.awards_path') . "/" . $this->image);
        }
        return false;
    }
}
