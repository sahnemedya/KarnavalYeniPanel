<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferenceType extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = ['name', 'hit',  'lang_id'];

    public function languages()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
