<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKeys extends Model
{
    use HasFactory;

    protected $fillable = ["youtube_channel_id", "recaptcha_site_key", "recaptcha_secret_key"];

}
