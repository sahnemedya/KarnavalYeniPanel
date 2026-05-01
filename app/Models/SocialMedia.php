<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;

    protected $fillable = ['contacts_id', 'facebook', 'instagram', 'twitter', 'linkedin', 'youtube', 'tiktok', 'whatsapp', 'telegram', 'behance', 'pinterest', 'threads', 'reddit'];

    public function contact()
    {
        return $this->belongsTo(Contacts::class, 'contacts_id');
    }
}
