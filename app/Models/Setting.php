<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'site_title',
        'meta_description',
        'meta_keywords',
        'logo',
        'favicon',
        'contact_email',
        'contact_phone',
        'address',
        'instagram_url',
        'facebook_url',
        'footer_text',
    ];
}
