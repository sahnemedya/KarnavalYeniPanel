<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcasts extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'page_id',
        'lang_id',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
