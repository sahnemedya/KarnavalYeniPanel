<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    use HasFactory;

    protected $fillable = ['site_name', 'seo_title', 'description', 'logo', 'favicon', 'footer_logo', 'head_code', 'header_code', 'footer_code'];

}
