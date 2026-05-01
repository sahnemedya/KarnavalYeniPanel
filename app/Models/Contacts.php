<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacts extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'email', 'email2', 'phone', 'phone2', 'address', 'country', 'city', 'state', 'person', 'map', 'hit'];

    public function socialMedia()
    {
        return $this->hasOne(SocialMedia::class);
    }

}
