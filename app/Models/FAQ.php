<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FAQ extends Model
{
    Use HasFactory;

    protected $fillable = ['page_id','question','answer','hit','lang_id'];
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
